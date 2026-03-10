<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\TrangThaiDatPhong;
use App\Models\DatPhong;
use Illuminate\Http\Request;

class TrangThaiDatPhongController extends BaseController
{
    /**
     * Hiển thị danh sách trạng thái đặt phòng
     */
    public function index(Request $request)
    {
        $query = TrangThaiDatPhong::withCount('datPhong');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('TenTrangThaiDP', 'like', '%' . $request->search . '%');
        }

        $trangThaiDatPhongs = $query->orderBy('MaTrangThaiDP', 'desc')->paginate(10);

        // Thống kê
        $tongTrangThai = TrangThaiDatPhong::count();
        $tongDatPhong = DatPhong::count();

        return view('admin.TrangThaiDatPhong.index', compact(
            'trangThaiDatPhongs',
            'tongTrangThai',
            'tongDatPhong'
        ));
    }

    /**
     * Hiển thị form thêm trạng thái đặt phòng mới
     */
    public function create()
    {
        return view('admin.TrangThaiDatPhong.create');
    }

    /**
     * Lưu trạng thái đặt phòng mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'TenTrangThaiDP' => 'required|string|max:255|unique:trang_thai_dat_phong,TenTrangThaiDP',
        ], [
            'TenTrangThaiDP.required' => 'Vui lòng nhập tên trạng thái',
            'TenTrangThaiDP.unique' => 'Tên trạng thái đã tồn tại',
            'TenTrangThaiDP.max' => 'Tên trạng thái không được quá 255 ký tự',
        ]);

        TrangThaiDatPhong::create([
            'TenTrangThaiDP' => $request->TenTrangThaiDP,
        ]);

        return redirect()->route('admin.trang-thai-dat-phong.index')
            ->with('success', 'Thêm trạng thái đặt phòng mới thành công!');
    }

    /**
     * Hiển thị chi tiết trạng thái đặt phòng
     */
    public function show($id)
    {
        $trangThaiDatPhong = TrangThaiDatPhong::with(['datPhong.phong', 'datPhong.khachHang'])->findOrFail($id);

        // Thống kê cho trạng thái đặt phòng
        $tongDatPhong = DatPhong::count();
        $soDatPhongTrangThai = $trangThaiDatPhong->datPhong->count();
        $tyLe = $tongDatPhong > 0 ? round(($soDatPhongTrangThai / $tongDatPhong) * 100, 1) : 0;

        return view('admin.TrangThaiDatPhong.show', compact(
            'trangThaiDatPhong',
            'tongDatPhong',
            'tyLe'
        ));
    }

    /**
     * Hiển thị form sửa trạng thái đặt phòng
     */
    public function edit($id)
    {
        $trangThaiDatPhong = TrangThaiDatPhong::with('datPhong')->findOrFail($id);
        return view('admin.TrangThaiDatPhong.edit', compact('trangThaiDatPhong'));
    }

    /**
     * Cập nhật thông tin trạng thái đặt phòng
     */
    public function update(Request $request, $id)
    {
        $trangThaiDatPhong = TrangThaiDatPhong::findOrFail($id);

        $request->validate([
            'TenTrangThaiDP' => 'required|string|max:255|unique:trang_thai_dat_phong,TenTrangThaiDP,' . $id . ',MaTrangThaiDP',
        ], [
            'TenTrangThaiDP.required' => 'Vui lòng nhập tên trạng thái',
            'TenTrangThaiDP.unique' => 'Tên trạng thái đã tồn tại',
            'TenTrangThaiDP.max' => 'Tên trạng thái không được quá 255 ký tự',
        ]);

        $trangThaiDatPhong->update([
            'TenTrangThaiDP' => $request->TenTrangThaiDP,
        ]);

        return redirect()->route('admin.trang-thai-dat-phong.index')
            ->with('success', 'Cập nhật trạng thái đặt phòng thành công!');
    }

    /**
     * Xóa trạng thái đặt phòng
     */
    public function destroy($id)
    {
        $trangThaiDatPhong = TrangThaiDatPhong::findOrFail($id);

        // Kiểm tra xem trạng thái đặt phòng có đang được sử dụng không
        if ($trangThaiDatPhong->datPhong()->exists()) {
            return redirect()->route('admin.trang-thai-dat-phong.index')
                ->with('error', 'Không thể xóa trạng thái đặt phòng đang có đặt phòng sử dụng!');
        }

        $trangThaiDatPhong->delete();

        return redirect()->route('admin.trang-thai-dat-phong.index')
            ->with('success', 'Xóa trạng thái đặt phòng thành công!');
    }
}
