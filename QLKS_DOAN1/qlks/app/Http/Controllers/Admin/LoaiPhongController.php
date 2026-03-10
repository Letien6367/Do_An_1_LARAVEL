<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\LoaiPhong;
use App\Models\Phong;
use Illuminate\Http\Request;

class LoaiPhongController extends BaseController
{
    /**
     * Hiển thị danh sách loại phòng
     */
    public function index(Request $request)
    {
        $query = LoaiPhong::withCount('phong');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('TenLoaiPhong', 'like', '%' . $request->search . '%');
        }

        $loaiPhongs = $query->orderBy('MaLoaiPhong', 'desc')->paginate(10);

        // Thống kê
        $tongLoaiPhong = LoaiPhong::count();
        $tongPhong = Phong::count();

        return view('admin.LoaiPhong.index', compact(
            'loaiPhongs',
            'tongLoaiPhong',
            'tongPhong'
        ));
    }

    /**
     * Hiển thị form thêm loại phòng mới
     */
    public function create()
    {
        return view('admin.LoaiPhong.create');
    }

    /**
     * Lưu loại phòng mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'TenLoaiPhong' => 'required|string|max:255|unique:loai_phong,TenLoaiPhong',
            'MoTa' => 'nullable|string',
        ], [
            'TenLoaiPhong.required' => 'Vui lòng nhập tên loại phòng',
            'TenLoaiPhong.unique' => 'Tên loại phòng đã tồn tại',
            'TenLoaiPhong.max' => 'Tên loại phòng không được quá 255 ký tự',
        ]);

        LoaiPhong::create([
            'TenLoaiPhong' => $request->TenLoaiPhong,
        ]);

        return redirect()->route('admin.loai-phong.index')
            ->with('success', 'Thêm loại phòng mới thành công!');
    }

    /**
     * Hiển thị chi tiết loại phòng
     */
    public function show($id)
    {
        $loaiPhong = LoaiPhong::with(['phong.trangThaiPhong'])->findOrFail($id);

        // Thống kê cho loại phòng
        $tongPhong = $loaiPhong->phong->count();
        $phongTrong = $loaiPhong->phong->filter(function($p) {
            return $p->trangThaiPhong && str_contains(strtolower($p->trangThaiPhong->TenTrangThai), 'trống');
        })->count();
        $phongDangSD = $loaiPhong->phong->filter(function($p) {
            return $p->trangThaiPhong && (str_contains(strtolower($p->trangThaiPhong->TenTrangThai), 'sử dụng') || str_contains(strtolower($p->trangThaiPhong->TenTrangThai), 'có khách'));
        })->count();

        return view('admin.LoaiPhong.show', compact(
            'loaiPhong',
            'tongPhong',
            'phongTrong',
            'phongDangSD'
        ));
    }

    /**
     * Hiển thị form sửa loại phòng
     */
    public function edit($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);
        return view('admin.LoaiPhong.edit', compact('loaiPhong'));
    }

    /**
     * Cập nhật thông tin loại phòng
     */
    public function update(Request $request, $id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);

        $request->validate([
            'TenLoaiPhong' => 'required|string|max:255|unique:loai_phong,TenLoaiPhong,' . $id . ',MaLoaiPhong',
            'MoTa' => 'nullable|string',
        ], [
            'TenLoaiPhong.required' => 'Vui lòng nhập tên loại phòng',
            'TenLoaiPhong.unique' => 'Tên loại phòng đã tồn tại',
            'TenLoaiPhong.max' => 'Tên loại phòng không được quá 255 ký tự',
        ]);

        $loaiPhong->update([
            'TenLoaiPhong' => $request->TenLoaiPhong,
        ]);

        return redirect()->route('admin.loai-phong.index')
            ->with('success', 'Cập nhật loại phòng thành công!');
    }

    /**
     * Xóa loại phòng
     */
    public function destroy($id)
    {
        $loaiPhong = LoaiPhong::findOrFail($id);

        // Kiểm tra xem loại phòng có đang được sử dụng không
        if ($loaiPhong->phong()->exists()) {
            return redirect()->route('admin.loai-phong.index')
                ->with('error', 'Không thể xóa loại phòng đang có phòng sử dụng!');
        }

        $loaiPhong->delete();

        return redirect()->route('admin.loai-phong.index')
            ->with('success', 'Xóa loại phòng thành công!');
    }
}
