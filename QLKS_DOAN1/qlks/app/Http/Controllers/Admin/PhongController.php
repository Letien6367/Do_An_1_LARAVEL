<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Phong;
use App\Models\LoaiPhong;
use App\Models\TrangThaiPhong;
use Illuminate\Http\Request;

class PhongController extends BaseController
{
    /**
     * Hiển thị danh sách phòng
     */
    public function index(Request $request)
    {
        $query = Phong::with(['loaiPhong', 'trangThaiPhong']);

        // Tìm kiếm theo tên phòng
        if ($request->filled('search')) {
            $query->where('TenPhong', 'like', '%' . $request->search . '%');
        }

        // Lọc theo loại phòng
        if ($request->filled('loai_phong')) {
            $query->where('MaLoaiPhong', $request->loai_phong);
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('MaTrangThai', $request->trang_thai);
        }

        $phongs = $query->orderBy('MaPhong', 'desc')->paginate(10);

        // Thống kê
        $tongPhong = Phong::count();
        $phongTrong = Phong::whereHas('trangThaiPhong', function($q) {
            $q->where('TenTrangThai', 'like', '%trống%');
        })->count();
        $phongDangSuDung = Phong::whereHas('trangThaiPhong', function($q) {
            $q->where('TenTrangThai', 'like', '%sử dụng%')
              ->orWhere('TenTrangThai', 'like', '%có khách%');
        })->count();
        $phongBaoTri = Phong::whereHas('trangThaiPhong', function($q) {
            $q->where('TenTrangThai', 'like', '%bảo trì%');
        })->count();

        // Dữ liệu cho filter
        $loaiPhongs = LoaiPhong::all();
        $trangThais = TrangThaiPhong::all();

        return view('admin.Phong.index', compact(
            'phongs',
            'tongPhong',
            'phongTrong',
            'phongDangSuDung',
            'phongBaoTri',
            'loaiPhongs',
            'trangThais'
        ));
    }

    /**
     * Hiển thị form thêm phòng mới
     */
    public function create()
    {
        $loaiPhongs = LoaiPhong::all();
        $trangThais = TrangThaiPhong::all();

        return view('admin.Phong.create', compact('loaiPhongs', 'trangThais'));
    }

    /**
     * Lưu phòng mới vào database
     */
    public function store(Request $request)
    {
        $request->validate([
            'TenPhong' => 'required|string|max:255|unique:phong,TenPhong',
            'MaLoaiPhong' => 'required|exists:loai_phong,MaLoaiPhong',
            'SoNguoi' => 'required|integer|min:1|max:10',
            'GiaPhong' => 'required|numeric|min:0',
            'MaTrangThai' => 'required|exists:trang_thai_phong,MaTrangThai',
        ], [
            'TenPhong.required' => 'Vui lòng nhập tên phòng',
            'TenPhong.unique' => 'Tên phòng đã tồn tại',
            'MaLoaiPhong.required' => 'Vui lòng chọn loại phòng',
            'MaLoaiPhong.exists' => 'Loại phòng không tồn tại',
            'SoNguoi.required' => 'Vui lòng nhập số người',
            'SoNguoi.min' => 'Số người tối thiểu là 1',
            'SoNguoi.max' => 'Số người tối đa là 10',
            'GiaPhong.required' => 'Vui lòng nhập giá phòng',
            'GiaPhong.min' => 'Giá phòng không được âm',
            'MaTrangThai.required' => 'Vui lòng chọn trạng thái',
            'MaTrangThai.exists' => 'Trạng thái không tồn tại',
        ]);

        Phong::create($request->only([
            'TenPhong',
            'MaLoaiPhong',
            'SoNguoi',
            'GiaPhong',
            'MaTrangThai'
        ]));

        return redirect()->route('admin.phong.index')
            ->with('success', 'Thêm phòng mới thành công!');
    }

    /**
     * Hiển thị chi tiết phòng
     */
    public function show($id)
    {
        $phong = Phong::with(['loaiPhong', 'trangThaiPhong', 'datPhong.khachHang', 'datPhong.trangThaiDatPhong'])
            ->findOrFail($id);

        // Thống kê cho phòng
        $tongDatPhong = $phong->datPhong->count();
        $doanhThu = $phong->hoaDon->sum('TongTien') / 1000000; // Đổi sang triệu
        $tyLeSD = $tongDatPhong > 0 ? round(($tongDatPhong / 30) * 100, 1) : 0;
        $danhGia = 'N/A';

        return view('admin.Phong.show', compact(
            'phong',
            'tongDatPhong',
            'doanhThu',
            'tyLeSD',
            'danhGia'
        ));
    }

    /**
     * Hiển thị form sửa phòng
     */
    public function edit($id)
    {
        $phong = Phong::findOrFail($id);
        $loaiPhongs = LoaiPhong::all();
        $trangThais = TrangThaiPhong::all();

        return view('admin.Phong.editPhong', compact('phong', 'loaiPhongs', 'trangThais'));
    }

    /**
     * Cập nhật thông tin phòng
     */
    public function update(Request $request, $id)
    {
        $phong = Phong::findOrFail($id);

        $request->validate([
            'TenPhong' => 'required|string|max:255|unique:phong,TenPhong,' . $id . ',MaPhong',
            'MaLoaiPhong' => 'required|exists:loai_phong,MaLoaiPhong',
            'SoNguoi' => 'required|integer|min:1|max:10',
            'GiaPhong' => 'required|numeric|min:0',
            'MaTrangThai' => 'required|exists:trang_thai_phong,MaTrangThai',
        ], [
            'TenPhong.required' => 'Vui lòng nhập tên phòng',
            'TenPhong.unique' => 'Tên phòng đã tồn tại',
            'MaLoaiPhong.required' => 'Vui lòng chọn loại phòng',
            'MaLoaiPhong.exists' => 'Loại phòng không tồn tại',
            'SoNguoi.required' => 'Vui lòng nhập số người',
            'SoNguoi.min' => 'Số người tối thiểu là 1',
            'SoNguoi.max' => 'Số người tối đa là 10',
            'GiaPhong.required' => 'Vui lòng nhập giá phòng',
            'GiaPhong.min' => 'Giá phòng không được âm',
            'MaTrangThai.required' => 'Vui lòng chọn trạng thái',
            'MaTrangThai.exists' => 'Trạng thái không tồn tại',
        ]);

        $phong->update($request->only([
            'TenPhong',
            'MaLoaiPhong',
            'SoNguoi',
            'GiaPhong',
            'MaTrangThai'
        ]));

        return redirect()->route('admin.phong.index')
            ->with('success', 'Cập nhật phòng thành công!');
    }

    /**
     * Xóa phòng
     */
    public function destroy($id)
    {
        $phong = Phong::findOrFail($id);

        // Kiểm tra xem phòng có đang được sử dụng không
        if ($phong->datPhong()->exists()) {
            return redirect()->route('admin.phong.index')
                ->with('error', 'Không thể xóa phòng đang có lịch sử đặt phòng!');
        }

        $phong->delete();

        return redirect()->route('admin.phong.index')
            ->with('success', 'Xóa phòng thành công!');
    }
}
