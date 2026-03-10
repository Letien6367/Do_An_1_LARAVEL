<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\TrangThaiPhong;
use App\Models\Phong;
use Illuminate\Http\Request;

class TrangThaiPhongController extends BaseController
{
    /**
     * Hiển thị danh sách trạng thái phòng
     */
    public function index(Request $request)
    {
        $query = TrangThaiPhong::withCount('phong');

        // Tìm kiếm theo tên
        if ($request->filled('search')) {
            $query->where('TenTrangThai', 'like', '%' . $request->search . '%');
        }

        $trangThaiPhongs = $query->orderBy('MaTrangThai', 'desc')->paginate(10);

        // Thống kê
        $tongTrangThai = TrangThaiPhong::count();
        $tongPhong = Phong::count();

        return view('admin.TrangThaiPhong.index', compact(
            'trangThaiPhongs',
            'tongTrangThai',
            'tongPhong'
        ));
    }

    /**
     * Hiển thị form thêm trạng thái phòng mới
     */
    public function create()
    {
        return view('admin.TrangThaiPhong.create');
    }

    /**
     * Lưu trạng thái phòng mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'TenTrangThai' => 'required|string|max:255|unique:trang_thai_phong,TenTrangThai',
        ], [
            'TenTrangThai.required' => 'Vui lòng nhập tên trạng thái',
            'TenTrangThai.unique' => 'Tên trạng thái đã tồn tại',
            'TenTrangThai.max' => 'Tên trạng thái không được quá 255 ký tự',
        ]);

        TrangThaiPhong::create([
            'TenTrangThai' => $request->TenTrangThai,
        ]);

        return redirect()->route('admin.trang-thai-phong.index')
            ->with('success', 'Thêm trạng thái phòng mới thành công!');
    }

    /**
     * Hiển thị chi tiết trạng thái phòng
     */
    public function show($id)
    {
        $trangThaiPhong = TrangThaiPhong::with(['phong.loaiPhong'])->findOrFail($id);

        // Thống kê cho trạng thái phòng
        $tongPhong = Phong::count();
        $soPhongTrangThai = $trangThaiPhong->phong->count();
        $tyLe = $tongPhong > 0 ? round(($soPhongTrangThai / $tongPhong) * 100, 1) : 0;

        return view('admin.TrangThaiPhong.show', compact(
            'trangThaiPhong',
            'tongPhong',
            'tyLe'
        ));
    }

    /**
     * Hiển thị form sửa trạng thái phòng
     */
    public function edit($id)
    {
        $trangThaiPhong = TrangThaiPhong::with('phong')->findOrFail($id);
        return view('admin.TrangThaiPhong.edit', compact('trangThaiPhong'));
    }

    /**
     * Cập nhật thông tin trạng thái phòng
     */
    public function update(Request $request, $id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);

        $request->validate([
            'TenTrangThai' => 'required|string|max:255|unique:trang_thai_phong,TenTrangThai,' . $id . ',MaTrangThai',
        ], [
            'TenTrangThai.required' => 'Vui lòng nhập tên trạng thái',
            'TenTrangThai.unique' => 'Tên trạng thái đã tồn tại',
            'TenTrangThai.max' => 'Tên trạng thái không được quá 255 ký tự',
        ]);

        $trangThaiPhong->update([
            'TenTrangThai' => $request->TenTrangThai,
        ]);

        return redirect()->route('admin.trang-thai-phong.index')
            ->with('success', 'Cập nhật trạng thái phòng thành công!');
    }

    /**
     * Xóa trạng thái phòng
     */
    public function destroy($id)
    {
        $trangThaiPhong = TrangThaiPhong::findOrFail($id);

        // Kiểm tra xem trạng thái phòng có đang được sử dụng không
        if ($trangThaiPhong->phong()->exists()) {
            return redirect()->route('admin.trang-thai-phong.index')
                ->with('error', 'Không thể xóa trạng thái phòng đang có phòng sử dụng!');
        }

        $trangThaiPhong->delete();

        return redirect()->route('admin.trang-thai-phong.index')
            ->with('success', 'Xóa trạng thái phòng thành công!');
    }
}
