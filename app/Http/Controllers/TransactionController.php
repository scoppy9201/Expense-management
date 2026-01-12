<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        $query = Transaction::with('category')->where('user_id', $userId);

        // Search
        if ($request->filled('search')) {
            $query->where('ghi_chu', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('danh_muc_id')) {
            $query->where('category_id', $request->danh_muc_id);
        }

        // Filter by type
        if ($request->filled('loai')) {
            $query->where('loai_giao_dich', $request->loai);
        }

        // Filter by payment method
        if ($request->filled('phuong_thuc')) {
            $query->where('phuong_thuc_thanh_toan', $request->phuong_thuc);
        }

        // Filter by date range
        if ($request->filled('tu_ngay')) {
            $query->where('ngay_giao_dich', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->where('ngay_giao_dich', '<=', $request->den_ngay);
        }

        // Get transactions
        $transactions = $query->orderBy('ngay_giao_dich', 'desc')
                             ->orderBy('created_at', 'desc')
                             ->paginate(10);

        // Get categories for filter
        $categories = Category::where('user_id', $userId)
                             ->where('trang_thai', true)
                             ->orderBy('ten_danh_muc')
                             ->get();

        // Get wallets for reference
        $wallets = Wallet::where('user_id', $userId)
                        ->where('trang_thai', true)
                        ->with('category')
                        ->orderBy('category_id')
                        ->orderBy('ten_ngan_sach')
                        ->get();
                        
        // Calculate statistics
        $totalIncome = Transaction::where('user_id', $userId)
                                 ->where('loai_giao_dich', 'THU')
                                 ->sum('so_tien');

        $totalExpense = Transaction::where('user_id', $userId)
                                  ->where('loai_giao_dich', 'CHI')
                                  ->sum('so_tien');

        return view('transactions.index', compact(
            'transactions',
            'categories',
            'wallets',
            'totalIncome',
            'totalExpense'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'loai_giao_dich' => 'required|in:THU,CHI',
            'phuong_thuc_thanh_toan' => 'required|in:Tiền mặt,Chuyển khoản',
            'so_tien' => 'required|numeric|min:0',
            'ngay_giao_dich' => 'required|date',
            'ghi_chu' => 'nullable|string|max:500',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'loai_giao_dich.required' => 'Vui lòng chọn loại giao dịch',
            'loai_giao_dich.in' => 'Loại giao dịch không hợp lệ',
            'phuong_thuc_thanh_toan.required' => 'Vui lòng chọn phương thức thanh toán',
            'phuong_thuc_thanh_toan.in' => 'Phương thức thanh toán không hợp lệ',
            'so_tien.required' => 'Vui lòng nhập số tiền',
            'so_tien.numeric' => 'Số tiền phải là số',
            'so_tien.min' => 'Số tiền phải lớn hơn hoặc bằng 0',
            'ngay_giao_dich.required' => 'Vui lòng chọn ngày giao dịch',
            'ngay_giao_dich.date' => 'Ngày giao dịch không hợp lệ',
            'ghi_chu.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);

        DB::beginTransaction();
        try {
            // Kiểm tra category thuộc về user hiện tại
            $category = Category::where('id', $validated['category_id'])
                               ->where('user_id', Auth::id())
                               ->firstOrFail();
                               
            // Tìm ngân sách của danh mục này (nếu có)
            $wallet = Wallet::where('category_id', $validated['category_id'])
                           ->where('user_id', Auth::id())
                           ->where('trang_thai', true)
                           ->first();

            // Nếu là CHI và có ngân sách, kiểm tra số dư
            if ($validated['loai_giao_dich'] == 'CHI' && $wallet) {
                if ($wallet->so_du < $validated['so_tien']) {
                    DB::rollBack();
                    return redirect()->back()
                                   ->with('error', 'Ngân sách không đủ! Số dư hiện tại: ' . number_format($wallet->so_du, 0, ',', '.') . 'đ')
                                   ->withInput();
                }
            }

            // Tạo giao dịch
            Transaction::create([
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'loai_giao_dich' => $validated['loai_giao_dich'],
                'phuong_thuc_thanh_toan' => $validated['phuong_thuc_thanh_toan'],
                'so_tien' => $validated['so_tien'],
                'ngay_giao_dich' => $validated['ngay_giao_dich'],
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            // Cập nhật số dư ngân sách (nếu có)
            if ($wallet) {
                if ($validated['loai_giao_dich'] == 'THU') {
                    // THU nhập: tăng số dư
                    $wallet->increment('so_du', $validated['so_tien']);
                } else {
                    // CHI tiêu: giảm số dư
                    $wallet->decrement('so_du', $validated['so_tien']);
                }
            }

            DB::commit();

            return redirect()->route('transactions.index')
                           ->with('success', 'Thêm giao dịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function update(Request $request, Transaction $transaction)
    {
        // Kiểm tra giao dịch thuộc về user hiện tại
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'loai_giao_dich' => 'required|in:THU,CHI',
            'phuong_thuc_thanh_toan' => 'required|in:Tiền mặt,Chuyển khoản',
            'so_tien' => 'required|numeric|min:0',
            'ngay_giao_dich' => 'required|date',
            'ghi_chu' => 'nullable|string|max:500',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'loai_giao_dich.required' => 'Vui lòng chọn loại giao dịch',
            'loai_giao_dich.in' => 'Loại giao dịch không hợp lệ',
            'phuong_thuc_thanh_toan.required' => 'Vui lòng chọn phương thức thanh toán',
            'phuong_thuc_thanh_toan.in' => 'Phương thức thanh toán không hợp lệ',
            'so_tien.required' => 'Vui lòng nhập số tiền',
            'so_tien.numeric' => 'Số tiền phải là số',
            'so_tien.min' => 'Số tiền phải lớn hơn hoặc bằng 0',
            'ngay_giao_dich.required' => 'Vui lòng chọn ngày giao dịch',
            'ngay_giao_dich.date' => 'Ngày giao dịch không hợp lệ',
            'ghi_chu.max' => 'Ghi chú không được vượt quá 500 ký tự',
        ]);

        DB::beginTransaction();
        try {
            // Kiểm tra category thuộc về user hiện tại
            $category = Category::where('id', $validated['category_id'])
                               ->where('user_id', Auth::id())
                               ->firstOrFail();

            // Lưu thông tin giao dịch cũ
            $oldCategoryId = $transaction->category_id;
            $oldAmount = $transaction->so_tien;
            $oldType = $transaction->loai_giao_dich;

            // Lấy ngân sách cũ (nếu có)
            $oldWallet = Wallet::where('category_id', $oldCategoryId)
                              ->where('user_id', Auth::id())
                              ->where('trang_thai', true)
                              ->first();

            // Lấy ngân sách mới (nếu có)
            $newWallet = Wallet::where('category_id', $validated['category_id'])
                              ->where('user_id', Auth::id())
                              ->where('trang_thai', true)
                              ->first();

            // TRƯỜNG HỢP 1: CÙNG DANH MỤC
            if ($oldCategoryId == $validated['category_id']) {
                if ($oldWallet) {
                    // Hoàn trả số tiền cũ
                    if ($oldType == 'THU') {
                        $oldWallet->decrement('so_du', $oldAmount);
                    } else {
                        $oldWallet->increment('so_du', $oldAmount);
                    }

                    // Kiểm tra số dư trước khi trừ (nếu là CHI)
                    if ($validated['loai_giao_dich'] == 'CHI') {
                        if ($oldWallet->so_du < $validated['so_tien']) {
                            DB::rollBack();
                            return redirect()->back()
                                           ->with('error', 'Ngân sách không đủ! Số dư hiện tại: ' . number_format($oldWallet->so_du, 0, ',', '.') . 'đ')
                                           ->withInput();
                        }
                    }

                    // Áp dụng số tiền mới
                    if ($validated['loai_giao_dich'] == 'THU') {
                        $oldWallet->increment('so_du', $validated['so_tien']);
                    } else {
                        $oldWallet->decrement('so_du', $validated['so_tien']);
                    }
                }
            }
            // TRƯỜNG HỢP 2: KHÁC DANH MỤC
            else {
                // Hoàn trả ngân sách cũ (nếu có)
                if ($oldWallet) {
                    if ($oldType == 'THU') {
                        $oldWallet->decrement('so_du', $oldAmount);
                    } else {
                        $oldWallet->increment('so_du', $oldAmount);
                    }
                }

                // Kiểm tra và áp dụng ngân sách mới (nếu có)
                if ($newWallet) {
                    // Kiểm tra số dư trước khi trừ (nếu là CHI)
                    if ($validated['loai_giao_dich'] == 'CHI') {
                        if ($newWallet->so_du < $validated['so_tien']) {
                            DB::rollBack();
                            return redirect()->back()
                                           ->with('error', 'Ngân sách mới không đủ! Số dư hiện tại: ' . number_format($newWallet->so_du, 0, ',', '.') . 'đ')
                                           ->withInput();
                        }
                    }

                    // Áp dụng số tiền mới
                    if ($validated['loai_giao_dich'] == 'THU') {
                        $newWallet->increment('so_du', $validated['so_tien']);
                    } else {
                        $newWallet->decrement('so_du', $validated['so_tien']);
                    }
                }
            }

            // Cập nhật giao dịch
            $transaction->update([
                'category_id' => $validated['category_id'],
                'loai_giao_dich' => $validated['loai_giao_dich'],
                'phuong_thuc_thanh_toan' => $validated['phuong_thuc_thanh_toan'],
                'so_tien' => $validated['so_tien'],
                'ngay_giao_dich' => $validated['ngay_giao_dich'],
                'ghi_chu' => $validated['ghi_chu'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('transactions.index')
                           ->with('success', 'Cập nhật giao dịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                           ->withInput();
        }
    }

    public function destroy(Transaction $transaction)
    {
        // Kiểm tra giao dịch thuộc về user hiện tại
        if ($transaction->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            // Hoàn trả số dư ngân sách về như ban đầu (nếu có)
            $wallet = Wallet::where('category_id', $transaction->category_id)
                           ->where('user_id', Auth::id())
                           ->where('trang_thai', true)
                           ->first();

            if ($wallet) {
                if ($transaction->loai_giao_dich == 'THU') {
                    // Thu nhập: trừ lại số dư (hoàn trả)
                    $wallet->decrement('so_du', $transaction->so_tien);
                } else {
                    // Chi tiêu: cộng lại số dư (hoàn trả)
                    $wallet->increment('so_du', $transaction->so_tien);
                }
            }

            $transaction->delete();
            
            DB::commit();

            return redirect()->route('transactions.index')
                           ->with('success', 'Xóa giao dịch thành công!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}