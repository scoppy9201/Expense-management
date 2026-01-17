<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    // Hiển thị danh sách ngân sách 
    public function index(Request $request)
    {
        $query = Wallet::with('category')
            ->where('user_id', Auth::id());

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $search = trim($request->search);
            $searchEscaped = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $search);
            $query->where('ten_ngan_sach', 'like', '%' . $searchEscaped . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            // Kiểm tra category phải thuộc user
            $categoryExists = Category::where('id', $request->category_id)
                                     ->where('user_id', Auth::id())
                                     ->exists();
            
            if ($categoryExists) {
                $query->where('category_id', $request->category_id);
            }
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        if (in_array($sortBy, ['ten_ngan_sach', 'ngan_sach_goc', 'so_du', 'created_at'])) {
            $query->orderBy($sortBy, $sortOrder);
        }

        // Phân trang
        $wallets = $query->paginate(10)->withQueryString();

        // Lấy danh mục con CHI
        $categories = Category::where('user_id', Auth::id())
            ->where('trang_thai', true)
            ->where('loai_danh_muc', 'CHI')           
            ->whereNotNull('danh_muc_cha_id')        
            ->orderBy('ten_danh_muc')
            ->get();

        return view('wallets.index', compact('wallets', 'categories'));
    }

    // Thêm ngân sách mới 
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_ngan_sach' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s\.,\-\(\)]*$/u',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);
                    if (!$category || $category->user_id !== Auth::id()) {
                        $fail('Danh mục không hợp lệ!');
                    }
                    if ($category->loai_danh_muc !== 'CHI') {
                        $fail('Chỉ có thể tạo ngân sách cho danh mục CHI!');
                    }
                    if (!$category->danh_muc_cha_id) {
                        $fail('Chỉ có thể chọn danh mục con!');
                    }
                }
            ],
            'ngan_sach_goc' => [
                'required',
                'numeric',
                'min:1000',
                'max:100000000',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'mo_ta' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\p{L}\p{N}\s\.,!?@#\-\(\)]*$/u',
            ],
        ], [
            'ten_ngan_sach.required' => 'Vui lòng nhập tên ngân sách',
            'ten_ngan_sach.max' => 'Tên ngân sách không được vượt quá 255 ký tự',
            'ten_ngan_sach.regex' => 'Tên ngân sách chứa ký tự không hợp lệ',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'ngan_sach_goc.required' => 'Vui lòng nhập hạn mức ngân sách',
            'ngan_sach_goc.numeric' => 'Hạn mức phải là số hợp lệ',
            'ngan_sach_goc.min' => 'Hạn mức phải từ 1,000 VNĐ trở lên',
            'ngan_sach_goc.max' => 'Hạn mức không được vượt quá 100,000,000 VNĐ (100 triệu)',
            'ngan_sach_goc.regex' => 'Hạn mức không hợp lệ. Chỉ được nhập số và tối đa 2 chữ số thập phân',
            'mo_ta.max' => 'Mô tả không được vượt quá 500 ký tự',
            'mo_ta.regex' => 'Mô tả chứa ký tự không hợp lệ',
        ]);

        // Trim dữ liệu
        $validated['ten_ngan_sach'] = trim($validated['ten_ngan_sach']);
        $validated['ngan_sach_goc'] = trim($validated['ngan_sach_goc']);
        $validated['mo_ta'] = $validated['mo_ta'] ? trim($validated['mo_ta']) : null;

        DB::beginTransaction();
        try {
            // Kiểm tra category phải là danh mục con CHI
            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', Auth::id())
                ->where('loai_danh_muc', 'CHI')
                ->whereNotNull('danh_muc_cha_id')
                ->first();

            if (!$category) {
                DB::rollBack();
                return back()
                    ->with('error', 'Chỉ có thể tạo ngân sách cho danh mục con loại chi!')
                    ->withInput();
            }

            // Kiểm tra xem đã có ngân sách active cho danh mục này chưa
            $existingWallet = Wallet::where('user_id', Auth::id())
                ->where('category_id', $validated['category_id'])
                ->where('trang_thai', true)
                ->exists();

            if ($existingWallet) {
                DB::rollBack();
                return back()
                    ->with('error', 'Danh mục "' . $category->ten_danh_muc . '" đã có ngân sách đang hoạt động!')
                    ->withInput();
            }

            // Tạo ngân sách mới 
            Wallet::create([
                'user_id' => Auth::id(),
                'category_id' => $validated['category_id'],
                'ten_ngan_sach' => $validated['ten_ngan_sach'],
                'ngan_sach_goc' => $validated['ngan_sach_goc'],
                'so_du' => $validated['ngan_sach_goc'], 
                'mo_ta' => $validated['mo_ta'],
                'trang_thai' => true,
            ]);

            DB::commit();
            
            return redirect()->route('wallets.index')
                ->with('success', 'Thêm ngân sách thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Cập nhật ngân sách 
    public function update(Request $request, Wallet $wallet)
    {
        // Kiểm tra quyền sở hữu
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'ten_ngan_sach' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\p{N}\s\.,\-\(\)]*$/u',
            ],
            'category_id' => [
                'required',
                'exists:categories,id',
                function ($attribute, $value, $fail) {
                    $category = Category::find($value);
                    if (!$category || $category->user_id !== Auth::id()) {
                        $fail('Danh mục không hợp lệ!');
                    }
                    if ($category->loai_danh_muc !== 'CHI') {
                        $fail('Chỉ có thể chọn danh mục CHI!');
                    }
                    if (!$category->danh_muc_cha_id) {
                        $fail('Chỉ có thể chọn danh mục con!');
                    }
                }
            ],
            'ngan_sach_goc' => [
                'required',
                'numeric',
                'min:1000',
                'max:100000000',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
            'mo_ta' => [
                'nullable',
                'string',
                'max:500',
                'regex:/^[\p{L}\p{N}\s\.,!?@#\-\(\)]*$/u',
            ],
        ], [
            'ten_ngan_sach.required' => 'Vui lòng nhập tên ngân sách',
            'ten_ngan_sach.max' => 'Tên ngân sách không được vượt quá 255 ký tự',
            'ten_ngan_sach.regex' => 'Tên ngân sách chứa ký tự không hợp lệ',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'ngan_sach_goc.required' => 'Vui lòng nhập hạn mức ngân sách',
            'ngan_sach_goc.numeric' => 'Hạn mức phải là số hợp lệ',
            'ngan_sach_goc.min' => 'Hạn mức phải từ 1,000 VNĐ trở lên',
            'ngan_sach_goc.max' => 'Hạn mức không được vượt quá 100,000,000 VNĐ (100 triệu)',
            'ngan_sach_goc.regex' => 'Hạn mức không hợp lệ. Chỉ được nhập số và tối đa 2 chữ số thập phân',
            'mo_ta.max' => 'Mô tả không được vượt quá 500 ký tự',
            'mo_ta.regex' => 'Mô tả chứa ký tự không hợp lệ',
        ]);

        // Trim dữ liệu
        $validated['ten_ngan_sach'] = trim($validated['ten_ngan_sach']);
        $validated['ngan_sach_goc'] = trim($validated['ngan_sach_goc']);
        $validated['mo_ta'] = $validated['mo_ta'] ? trim($validated['mo_ta']) : null;

        DB::beginTransaction();
        try {
            // Kiểm tra category phải là danh mục con CHI
            $category = Category::where('id', $validated['category_id'])
                ->where('user_id', Auth::id())
                ->where('loai_danh_muc', 'CHI')
                ->whereNotNull('danh_muc_cha_id')
                ->first();

            if (!$category) {
                DB::rollBack();
                return back()
                    ->with('error', 'Chỉ có thể cập nhật cho danh mục con loại CHI!')
                    ->withInput();
            }

            // Nếu đổi danh mục
            if ($wallet->category_id != $validated['category_id']) {
                // Không cho đổi category nếu đã có giao dịch
                if ($wallet->transactions()->exists()) {
                    DB::rollBack();
                    return back()
                        ->with('error', 'Không thể đổi danh mục cho ngân sách đã có giao dịch!')
                        ->withInput();
                }
                
                // Kiểm tra xem danh mục mới đã có ngân sách active chưa
                $existingWallet = Wallet::where('user_id', Auth::id())
                    ->where('category_id', $validated['category_id'])
                    ->where('trang_thai', true)
                    ->where('id', '!=', $wallet->id)
                    ->exists();

                if ($existingWallet) {
                    DB::rollBack();
                    return back()
                        ->with('error', 'Danh mục "' . $category->ten_danh_muc . '" đã có ngân sách đang hoạt động!')
                        ->withInput();
                }

                // Reset số dư khi đổi danh mục (vì chưa có giao dịch)
                $wallet->update([
                    'ten_ngan_sach' => $validated['ten_ngan_sach'],
                    'category_id' => $validated['category_id'],
                    'ngan_sach_goc' => $validated['ngan_sach_goc'],
                    'so_du' => $validated['ngan_sach_goc'], 
                    'mo_ta' => $validated['mo_ta'],
                ]);
            } else {
                // Giữ nguyên danh mục - tính lại số dư
                $spentAmount = $wallet->spent_amount;
                $newBalance = $validated['ngan_sach_goc'] - $spentAmount;

                // Kiểm tra hạn mức mới phải >= số đã chi
                if ($newBalance < 0) {
                    DB::rollBack();
                    return back()
                        ->with('error', 'Hạn mức mới phải lớn hơn hoặc bằng số tiền đã chi (' . number_format($spentAmount, 0, ',', '.') . 'đ)!')
                        ->withInput();
                }

                $wallet->update([
                    'ten_ngan_sach' => $validated['ten_ngan_sach'],
                    'ngan_sach_goc' => $validated['ngan_sach_goc'],
                    'so_du' => $newBalance,
                    'mo_ta' => $validated['mo_ta'],
                ]);
            }

            DB::commit();
            
            return redirect()->route('wallets.index')
                ->with('success', 'Cập nhật ngân sách thành công!');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Có lỗi xảy ra: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Xóa ngân sách 
    public function destroy(Wallet $wallet)
    {
        // Kiểm tra quyền sở hữu
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            if (!$wallet->canDelete()) {
                DB::rollBack();
                return back()->with('error', 'Không thể xóa ngân sách đã có giao dịch!');
            }

            $walletName = $wallet->ten_ngan_sach;
            $wallet->delete();

            DB::commit();
            
            return redirect()->route('wallets.index')
                ->with('success', "Xóa ngân sách '{$walletName}' thành công!");
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Trạng thái của ngân sách 
    public function toggleStatus(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        
        try {
            $newStatus = !$wallet->trang_thai;
            
            if ($newStatus) {
                // Kiểm tra trùng lặp
                $existingActiveWallet = Wallet::where('user_id', Auth::id())
                    ->where('category_id', $wallet->category_id)
                    ->where('trang_thai', true)
                    ->where('id', '!=', $wallet->id)
                    ->exists();

                if ($existingActiveWallet) {
                    DB::rollBack();
                    return back()->with('error', 'Danh mục này đã có ngân sách đang hoạt động!');
                }

                // Kích hoạt + tính lại số dư 
                $wallet->update(['trang_thai' => true]);
                $newBalance = $wallet->recalculateBalance();

                DB::commit();

                return redirect()->route('wallets.index')
                    ->with('success', "Đã kích hoạt ngân sách '{$wallet->ten_ngan_sach}' và cập nhật số dư: " . number_format($newBalance, 0, ',', '.') . 'đ');
            } else {
                // Vô hiệu hóa
                $wallet->update(['trang_thai' => false]);

                DB::commit();

                return redirect()->route('wallets.index')
                    ->with('success', "Đã vô hiệu hóa ngân sách '{$wallet->ten_ngan_sach}' thành công!");
            }
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    // Đồng bộ số dư thủ công 
    public function syncBalance(Wallet $wallet)
    {
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        DB::beginTransaction();
        try {
            $newBalance = $wallet->recalculateBalance();
            
            DB::commit();
            
            return redirect()->route('wallets.index')
                ->with('success', "Đã đồng bộ số dư ngân sách '{$wallet->ten_ngan_sach}'. Số dư mới: " . number_format($newBalance, 0, ',', '.') . 'đ');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}