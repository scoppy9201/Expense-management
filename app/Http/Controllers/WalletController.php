<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Wallet::with('category')
            ->where('user_id', Auth::id());

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('ten_ngan_sach', 'like', '%' . $request->search . '%');
        }

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Sắp xếp
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $query->orderBy($sortBy, $sortOrder);

        // Phân trang
        $wallets = $query->paginate(10)->withQueryString();

        // Lấy danh sách categories cho filter và form
        $categories = Category::where('user_id', Auth::id())
            ->where('trang_thai', true)
            ->orderBy('ten_danh_muc')
            ->get();

        return view('wallets.index', compact('wallets', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ten_ngan_sach' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'ngan_sach_goc' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string|max:500',
        ], [
            'ten_ngan_sach.required' => 'Vui lòng nhập tên ngân sách',
            'ten_ngan_sach.max' => 'Tên ngân sách không được vượt quá 255 ký tự',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'ngan_sach_goc.required' => 'Vui lòng nhập hạn mức ngân sách',
            'ngan_sach_goc.numeric' => 'Hạn mức phải là số',
            'ngan_sach_goc.min' => 'Hạn mức phải lớn hơn hoặc bằng 0',
            'mo_ta.max' => 'Mô tả không được vượt quá 500 ký tự',
        ]);

        // Kiểm tra category thuộc về user
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$category) {
            return back()->with('error', 'Danh mục không hợp lệ!');
        }

        // Tạo wallet mới
        $wallet = Wallet::create([
            'user_id' => Auth::id(),
            'category_id' => $validated['category_id'],
            'ten_ngan_sach' => $validated['ten_ngan_sach'],
            'ngan_sach_goc' => $validated['ngan_sach_goc'],
            'so_du' => $validated['ngan_sach_goc'], 
            'mo_ta' => $validated['mo_ta'] ?? null,
            'trang_thai' => true,
        ]);

        return redirect()->route('wallets.index')
            ->with('success', 'Thêm ngân sách thành công!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wallet $wallet)
    {
        // Kiểm tra quyền sở hữu
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'ten_ngan_sach' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'ngan_sach_goc' => 'required|numeric|min:0',
            'mo_ta' => 'nullable|string|max:500',
        ], [
            'ten_ngan_sach.required' => 'Vui lòng nhập tên ngân sách',
            'ten_ngan_sach.max' => 'Tên ngân sách không được vượt quá 255 ký tự',
            'category_id.required' => 'Vui lòng chọn danh mục',
            'category_id.exists' => 'Danh mục không tồn tại',
            'ngan_sach_goc.required' => 'Vui lòng nhập hạn mức ngân sách',
            'ngan_sach_goc.numeric' => 'Hạn mức phải là số',
            'ngan_sach_goc.min' => 'Hạn mức phải lớn hơn hoặc bằng 0',
            'mo_ta.max' => 'Mô tả không được vượt quá 500 ký tự',
        ]);

        // Kiểm tra category thuộc về user
        $category = Category::where('id', $validated['category_id'])
            ->where('user_id', Auth::id())
            ->first();

        if (!$category) {
            return back()->with('error', 'Danh mục không hợp lệ!');
        }

        // Nếu đổi danh mục, cảnh báo user về việc số dư sẽ thay đổi
        if ($wallet->category_id != $validated['category_id']) {
            $wallet->update([
                'ten_ngan_sach' => $validated['ten_ngan_sach'],
                'category_id' => $validated['category_id'],
                'ngan_sach_goc' => $validated['ngan_sach_goc'],
                'mo_ta' => $validated['mo_ta'] ?? null,
            ]);
        } else {
            $spentAmount = $wallet->spent_amount;
            $newBalance = $validated['ngan_sach_goc'] - $spentAmount;

            $wallet->update([
                'ten_ngan_sach' => $validated['ten_ngan_sach'],
                'ngan_sach_goc' => $validated['ngan_sach_goc'],
                'so_du' => $newBalance,
                'mo_ta' => $validated['mo_ta'] ?? null,
            ]);
        }

        return redirect()->route('wallets.index')
            ->with('success', 'Cập nhật ngân sách thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Wallet $wallet)
    {
        // Kiểm tra quyền sở hữu
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Kiểm tra xem có giao dịch liên quan không 
        if ($wallet->transactions()->exists()) {
            return back()->with('error', 'Không thể xóa ngân sách đã có giao dịch!');
        }

        $wallet->delete();

        return redirect()->route('wallets.index')
            ->with('success', 'Xóa ngân sách thành công!');
    }

    /**
     * Toggle status của wallet
     */
    public function toggleStatus(Wallet $wallet)
    {
        // Kiểm tra quyền sở hữu
        if ($wallet->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $wallet->update([
            'trang_thai' => !$wallet->trang_thai
        ]);

        $status = $wallet->trang_thai ? 'kích hoạt' : 'vô hiệu hóa';
        
        return redirect()->route('wallets.index')
            ->with('success', "Đã {$status} ngân sách thành công!");
    }
}