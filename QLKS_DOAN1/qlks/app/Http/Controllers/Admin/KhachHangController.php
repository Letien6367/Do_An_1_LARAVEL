<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\KhachHang;
use App\Models\User;
use Illuminate\Http\Request;

class KhachHangController extends BaseController
{
    /**
     * Hiển thị danh sách khách hàng
     */
    public function index(Request $request)
    {
        $query = KhachHang::with('user');

        // Tìm kiếm
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('TenKhachHang', 'like', "%{$search}%")
                  ->orWhere('SoDienThoai', 'like', "%{$search}%")
                  ->orWhere('GiayChungMinh', 'like', "%{$search}%")
                  ->orWhere('DiaChi', 'like', "%{$search}%");
            });
        }

        $khachHangs = $query->orderBy('MaKhachHang', 'desc')->paginate(10);

        // Thống kê
        $tongKhachHang = KhachHang::count();
        $khachHangMoi = KhachHang::whereMonth('created_at', now()->month)->count();

        return view('admin.KhachHang.index', compact('khachHangs', 'tongKhachHang', 'khachHangMoi'));
    }

    /**
     * Hiển thị form tạo khách hàng mới
     */
    public function create()
    {
        $users = User::where('VaiTro', 'KhachHang')
                    ->whereDoesntHave('khachHang')
                    ->get();
        return view('admin.KhachHang.create', compact('users'));
    }

    /**
     * Lưu khách hàng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'TenKhachHang' => 'required|string|max:255',
            'NgaySinh' => 'nullable|date',
            'SoDienThoai' => 'required|string|max:20',
            'DiaChi' => 'nullable|string|max:500',
            'GiayChungMinh' => 'required|string|max:20|unique:khach_hang,GiayChungMinh',
            'MaTaiKhoan' => 'nullable|exists:users,id',
        ], [
            'TenKhachHang.required' => 'Vui lòng nhập tên khách hàng',
            'SoDienThoai.required' => 'Vui lòng nhập số điện thoại',
            'GiayChungMinh.required' => 'Vui lòng nhập số CMND/CCCD',
            'GiayChungMinh.unique' => 'Số CMND/CCCD đã tồn tại',
        ]);

        KhachHang::create($request->all());

        return redirect()->route('admin.khach-hang.index')
                        ->with('success', 'Thêm khách hàng thành công!');
    }

    /**
     * Hiển thị chi tiết khách hàng
     */
    public function show(KhachHang $khachHang)
    {
        $khachHang->load(['user', 'datPhong.phong', 'datPhong.trangThaiDatPhong']);
        return view('admin.KhachHang.show', compact('khachHang'));
    }

    /**
     * Hiển thị form chỉnh sửa khách hàng
     */
    public function edit(KhachHang $khachHang)
    {
        $users = User::where('VaiTro', 'KhachHang')
                    ->where(function($q) use ($khachHang) {
                        $q->whereDoesntHave('khachHang')
                          ->orWhere('id', $khachHang->MaTaiKhoan);
                    })
                    ->get();
        return view('admin.KhachHang.edit', compact('khachHang', 'users'));
    }

    /**
     * Cập nhật khách hàng
     */
    public function update(Request $request, KhachHang $khachHang)
    {
        $request->validate([
            'TenKhachHang' => 'required|string|max:255',
            'NgaySinh' => 'nullable|date',
            'SoDienThoai' => 'required|string|max:20',
            'DiaChi' => 'nullable|string|max:500',
            'GiayChungMinh' => 'required|string|max:20|unique:khach_hang,GiayChungMinh,' . $khachHang->MaKhachHang . ',MaKhachHang',
            'MaTaiKhoan' => 'nullable|exists:users,id',
        ], [
            'TenKhachHang.required' => 'Vui lòng nhập tên khách hàng',
            'SoDienThoai.required' => 'Vui lòng nhập số điện thoại',
            'GiayChungMinh.required' => 'Vui lòng nhập số CMND/CCCD',
            'GiayChungMinh.unique' => 'Số CMND/CCCD đã tồn tại',
        ]);

        $khachHang->update($request->all());

        return redirect()->route('admin.khach-hang.index')
                        ->with('success', 'Cập nhật khách hàng thành công!');
    }

    /**
     * Xóa khách hàng
     */
    public function destroy(KhachHang $khachHang)
    {
        // Kiểm tra xem khách hàng có đặt phòng không
        if ($khachHang->datPhong()->exists()) {
            return redirect()->route('admin.khach-hang.index')
                            ->with('error', 'Không thể xóa khách hàng đã có lịch sử đặt phòng!');
        }

        $khachHang->delete();

        return redirect()->route('admin.khach-hang.index')
                        ->with('success', 'Xóa khách hàng thành công!');
    }
}
