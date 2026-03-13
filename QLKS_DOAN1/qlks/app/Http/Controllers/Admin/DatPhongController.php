<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\DatPhong;
use App\Models\Phong;
use App\Models\KhachHang;
use App\Models\TrangThaiDatPhong;
use Illuminate\Http\Request;

class DatPhongController extends BaseController
{
    /**
     * Hiển thị danh sách đặt phòng
     */
    public function index(Request $request)
    {
        $query = DatPhong::with(['phong', 'khachHang', 'trangThaiDatPhong']);

        // Tìm kiếm theo tên khách hàng hoặc tên phòng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('khachHang', function($q2) use ($search) {
                    $q2->where('TenKhachHang', 'like', '%' . $search . '%');
                })->orWhereHas('phong', function($q2) use ($search) {
                    $q2->where('TenPhong', 'like', '%' . $search . '%');
                });
            });
        }

        // Lọc theo trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('MaTrangThaiDP', $request->trang_thai);
        }

        // Lọc theo ngày đặt
        if ($request->filled('tu_ngay')) {
            $query->whereDate('NgayDatPhong', '>=', $request->tu_ngay);
        }

        if ($request->filled('den_ngay')) {
            $query->whereDate('NgayDatPhong', '<=', $request->den_ngay);
        }

        $datPhongs = $query->orderBy('MaDatPhong', 'desc')->paginate(10);

        // Thống kê
        $tongDatPhong = DatPhong::count();
        $dangChoXacNhan = DatPhong::whereHas('trangThaiDatPhong', function($q) {
            $q->where('TenTrangThaiDP', 'like', '%chờ%');
        })->count();
        $daXacNhan = DatPhong::whereHas('trangThaiDatPhong', function($q) {
            $q->where('TenTrangThaiDP', 'like', '%xác nhận%');
        })->count();
        $daHuy = DatPhong::whereHas('trangThaiDatPhong', function($q) {
            $q->where('TenTrangThaiDP', 'like', '%hủy%');
        })->count();

        $trangThais = TrangThaiDatPhong::all();

        return view('admin.DatPhong.index', compact(
            'datPhongs',
            'tongDatPhong',
            'dangChoXacNhan',
            'daXacNhan',
            'daHuy',
            'trangThais'
        ));
    }

    /**
     * Hiển thị form đặt phòng mới
     */
    public function create()
    {
        $phongs = Phong::with(['loaiPhong', 'trangThaiPhong'])->get();
        $khachHangs = KhachHang::all();
        $trangThais = TrangThaiDatPhong::all();

        return view('admin.DatPhong.create', compact('phongs', 'khachHangs', 'trangThais'));
    }

    /**
     * Lưu đặt phòng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'MaPhong' => 'required|exists:phong,MaPhong',
            'MaKhachHang' => 'required|exists:khach_hang,MaKhachHang',
            'NgayDatPhong' => 'required|date',
            'NgayTraPhong' => 'required|date|after:NgayDatPhong',
            'MaTrangThaiDP' => 'required|exists:trang_thai_dat_phong,MaTrangThaiDP',
        ], [
            'MaPhong.required' => 'Vui lòng chọn phòng',
            'MaPhong.exists' => 'Phòng không tồn tại',
            'MaKhachHang.required' => 'Vui lòng chọn khách hàng',
            'MaKhachHang.exists' => 'Khách hàng không tồn tại',
            'NgayDatPhong.required' => 'Vui lòng chọn ngày đặt phòng',
            'NgayTraPhong.required' => 'Vui lòng chọn ngày trả phòng',
            'NgayTraPhong.after' => 'Ngày trả phòng phải sau ngày đặt phòng',
            'MaTrangThaiDP.required' => 'Vui lòng chọn trạng thái',
        ]);

        // Kiểm tra phòng có trống trong khoảng thời gian này không
        $conflict = DatPhong::where('MaPhong', $request->MaPhong)
            ->where(function($query) use ($request) {
                $query->whereBetween('NgayDatPhong', [$request->NgayDatPhong, $request->NgayTraPhong])
                    ->orWhereBetween('NgayTraPhong', [$request->NgayDatPhong, $request->NgayTraPhong])
                    ->orWhere(function($q) use ($request) {
                        $q->where('NgayDatPhong', '<=', $request->NgayDatPhong)
                          ->where('NgayTraPhong', '>=', $request->NgayTraPhong);
                    });
            })
            ->whereHas('trangThaiDatPhong', function($q) {
                $q->where('TenTrangThaiDP', 'not like', '%hủy%');
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Phòng đã được đặt trong khoảng thời gian này!');
        }

        DatPhong::create([
            'MaPhong' => $request->MaPhong,
            'MaKhachHang' => $request->MaKhachHang,
            'NgayDatPhong' => $request->NgayDatPhong,
            'NgayTraPhong' => $request->NgayTraPhong,
            'MaTrangThaiDP' => $request->MaTrangThaiDP,
        ]);

        return redirect()->route('admin.dat-phong.index')
            ->with('success', 'Đặt phòng thành công!');
    }

    /**
     * Hiển thị chi tiết đặt phòng
     */
    public function show($id)
    {
        $datPhong = DatPhong::with(['phong.loaiPhong', 'phong.trangThaiPhong', 'khachHang', 'trangThaiDatPhong', 'hoaDon'])->findOrFail($id);

        // Tính số ngày ở
        $ngayDat = \Carbon\Carbon::parse($datPhong->NgayDatPhong);
        $ngayTra = \Carbon\Carbon::parse($datPhong->NgayTraPhong);
        $soNgay = $ngayDat->diffInDays($ngayTra);

        // Tính tổng tiền
        $giaPhong = $datPhong->phong->GiaPhong ?? 0;
        $tongTien = $giaPhong * $soNgay;

        return view('admin.DatPhong.show', compact('datPhong', 'soNgay', 'tongTien'));
    }

    /**
     * Hiển thị form sửa đặt phòng
     */
    public function edit($id)
    {
        $datPhong = DatPhong::with(['phong', 'khachHang', 'trangThaiDatPhong'])->findOrFail($id);
        $phongs = Phong::with(['loaiPhong', 'trangThaiPhong'])->get();
        $khachHangs = KhachHang::all();
        $trangThais = TrangThaiDatPhong::all();

        return view('admin.DatPhong.edit', compact('datPhong', 'phongs', 'khachHangs', 'trangThais'));
    }

    /**
     * Cập nhật đặt phòng
     */
    public function update(Request $request, $id)
    {
        $datPhong = DatPhong::findOrFail($id);

        $request->validate([
            'MaPhong' => 'required|exists:phong,MaPhong',
            'MaKhachHang' => 'required|exists:khach_hang,MaKhachHang',
            'NgayDatPhong' => 'required|date',
            'NgayTraPhong' => 'required|date|after:NgayDatPhong',
            'MaTrangThaiDP' => 'required|exists:trang_thai_dat_phong,MaTrangThaiDP',
        ], [
            'MaPhong.required' => 'Vui lòng chọn phòng',
            'MaKhachHang.required' => 'Vui lòng chọn khách hàng',
            'NgayDatPhong.required' => 'Vui lòng chọn ngày đặt phòng',
            'NgayTraPhong.required' => 'Vui lòng chọn ngày trả phòng',
            'NgayTraPhong.after' => 'Ngày trả phòng phải sau ngày đặt phòng',
        ]);

        // Kiểm tra xung đột (trừ chính đơn đặt phòng này)
        $conflict = DatPhong::where('MaPhong', $request->MaPhong)
            ->where('MaDatPhong', '!=', $id)
            ->where(function($query) use ($request) {
                $query->whereBetween('NgayDatPhong', [$request->NgayDatPhong, $request->NgayTraPhong])
                    ->orWhereBetween('NgayTraPhong', [$request->NgayDatPhong, $request->NgayTraPhong])
                    ->orWhere(function($q) use ($request) {
                        $q->where('NgayDatPhong', '<=', $request->NgayDatPhong)
                          ->where('NgayTraPhong', '>=', $request->NgayTraPhong);
                    });
            })
            ->whereHas('trangThaiDatPhong', function($q) {
                $q->where('TenTrangThaiDP', 'not like', '%hủy%');
            })
            ->exists();

        if ($conflict) {
            return back()->withInput()->with('error', 'Phòng đã được đặt trong khoảng thời gian này!');
        }

        $datPhong->update([
            'MaPhong' => $request->MaPhong,
            'MaKhachHang' => $request->MaKhachHang,
            'NgayDatPhong' => $request->NgayDatPhong,
            'NgayTraPhong' => $request->NgayTraPhong,
            'MaTrangThaiDP' => $request->MaTrangThaiDP,
        ]);

        return redirect()->route('admin.dat-phong.index')
            ->with('success', 'Cập nhật đặt phòng thành công!');
    }

    /**
     * Xóa đặt phòng
     */
    public function destroy($id)
    {
        $datPhong = DatPhong::findOrFail($id);

        // Kiểm tra xem có hóa đơn liên quan không
        if ($datPhong->hoaDon()->exists()) {
            return redirect()->route('admin.dat-phong.index')
                ->with('error', 'Không thể xóa đặt phòng đã có hóa đơn!');
        }

        $datPhong->delete();

        return redirect()->route('admin.dat-phong.index')
            ->with('success', 'Xóa đặt phòng thành công!');
    }

    /**
     * Xác nhận nhanh đơn đặt phòng từ màn hình danh sách
     */
    public function xacNhan($id)
    {
        // Lấy đơn đặt phòng theo mã, nếu không có thì trả về 404
        $datPhong = DatPhong::with('trangThaiDatPhong')->findOrFail($id);

        // Lấy tên trạng thái hiện tại để kiểm tra có được phép cập nhật hay không
        $tenTrangThaiHienTai = mb_strtolower($datPhong->trangThaiDatPhong->TenTrangThaiDP ?? '');

        // Chỉ cho xác nhận khi đơn đang ở trạng thái chờ
        if (!str_contains($tenTrangThaiHienTai, 'chờ')) {
            return redirect()->route('admin.dat-phong.index')
                ->with('error', 'Chỉ đơn đang chờ mới có thể xác nhận.');
        }

        // Tìm trạng thái "Đã xác nhận" trong bảng trạng thái
        $trangThaiDaXacNhan = TrangThaiDatPhong::where('TenTrangThaiDP', 'like', '%xác nhận%')->first();

        // Nếu chưa có trạng thái "Đã xác nhận" thì báo lỗi để tránh lưu sai dữ liệu
        if (!$trangThaiDaXacNhan) {
            return redirect()->route('admin.dat-phong.index')
                ->with('error', 'Không tìm thấy trạng thái Đã xác nhận. Vui lòng kiểm tra dữ liệu trạng thái.');
        }

        // Cập nhật trạng thái đơn đặt phòng sang "Đã xác nhận"
        $datPhong->update([
            'MaTrangThaiDP' => $trangThaiDaXacNhan->MaTrangThaiDP,
        ]);

        // Quay lại trang danh sách với thông báo thành công
        return redirect()->route('admin.dat-phong.index')
            ->with('success', 'Đã xác nhận đơn đặt phòng thành công.');
    }

    /**
     * Hủy nhanh đơn đặt phòng từ màn hình danh sách
     */
    public function huy($id)
    {
        // Lấy đơn đặt phòng theo mã, nếu không có thì trả về 404
        $datPhong = DatPhong::with('trangThaiDatPhong')->findOrFail($id);

        // Lấy tên trạng thái hiện tại để kiểm tra điều kiện hủy
        $tenTrangThaiHienTai = mb_strtolower($datPhong->trangThaiDatPhong->TenTrangThaiDP ?? '');

        // Không cho hủy lại nếu đơn đã ở trạng thái hủy
        if (str_contains($tenTrangThaiHienTai, 'hủy')) {
            return redirect()->route('admin.dat-phong.index')
                ->with('error', 'Đơn này đã được hủy trước đó.');
        }

        // Tìm trạng thái "Đã hủy" trong bảng trạng thái
        $trangThaiDaHuy = TrangThaiDatPhong::where('TenTrangThaiDP', 'like', '%hủy%')->first();

        // Nếu chưa có trạng thái "Đã hủy" thì báo lỗi để tránh lưu sai dữ liệu
        if (!$trangThaiDaHuy) {
            return redirect()->route('admin.dat-phong.index')
                ->with('error', 'Không tìm thấy trạng thái Đã hủy. Vui lòng kiểm tra dữ liệu trạng thái.');
        }

        // Cập nhật trạng thái đơn đặt phòng sang "Đã hủy"
        $datPhong->update([
            'MaTrangThaiDP' => $trangThaiDaHuy->MaTrangThaiDP,
        ]);

        // Quay lại trang danh sách với thông báo thành công
        return redirect()->route('admin.dat-phong.index')
            ->with('success', 'Đã hủy đơn đặt phòng thành công.');
    }
}
