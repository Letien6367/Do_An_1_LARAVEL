<?php
/**
 * ===================================================================
 * CONTROLLER QUẢN LÝ HÓA ĐƠN (Admin)
 * ===================================================================
 * - Hiển thị danh sách hóa đơn (index)
 * - Tạo hóa đơn mới từ đặt phòng (create, store)
 * - Xem chi tiết hóa đơn (show)
 * - Xóa hóa đơn (destroy)
 * 
 * Hóa đơn được tạo dựa trên đặt phòng: TongTien = GiaPhong × Số đêm
 */

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\HoaDon;
use App\Models\DatPhong;
use App\Models\Phong;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HoaDonController extends BaseController
{
    /**
     * Hiển thị danh sách hóa đơn
     * - Hỗ trợ tìm kiếm theo tên phòng, tên khách hàng
     * - Hỗ trợ lọc theo khoảng ngày lập hóa đơn
     * - Phân trang mỗi lần 10 bản ghi
     */
    public function index(Request $request)
    {
        // Truy vấn hóa đơn kèm thông tin phòng, đặt phòng, khách hàng
        $query = HoaDon::with(['phong', 'datPhong.khachHang', 'datPhong.trangThaiDatPhong']);

        // Tìm kiếm theo tên phòng hoặc tên khách hàng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                // Tìm theo tên phòng
                $q->whereHas('phong', function ($q2) use ($search) {
                    $q2->where('TenPhong', 'like', '%' . $search . '%');
                })
                // Hoặc tìm theo tên khách hàng
                ->orWhereHas('datPhong.khachHang', function ($q2) use ($search) {
                    $q2->where('TenKhachHang', 'like', '%' . $search . '%');
                });
            });
        }
        // Lọc theo ngày lập hóa đơn: từ ngày
        if ($request->filled('tu_ngay')) {
            $query->whereDate('NgayLapHD', '>=', $request->tu_ngay);
        }

        // Lọc theo ngày lập hóa đơn: đến ngày
        if ($request->filled('den_ngay')) {
            $query->whereDate('NgayLapHD', '<=', $request->den_ngay);
        }

        // Sắp xếp mới nhất lên trước, phân trang 10 bản ghi
        $hoaDons = $query->orderBy('MaHoaDon', 'desc')->paginate(10);

        // === Thống kê tổng quan ===
        // Tổng số hóa đơn
        $tongHoaDon = HoaDon::count();

        // Tổng doanh thu từ tất cả hóa đơn
        $tongDoanhThu = HoaDon::sum('TongTien') ?? 0;

        // Số hóa đơn tháng này
        $hoaDonThangNay = HoaDon::whereMonth('NgayLapHD', Carbon::now()->month)
            ->whereYear('NgayLapHD', Carbon::now()->year)
            ->count();

        // Doanh thu tháng này
        $doanhThuThangNay = HoaDon::whereMonth('NgayLapHD', Carbon::now()->month)
            ->whereYear('NgayLapHD', Carbon::now()->year)
            ->sum('TongTien') ?? 0;

        return view('admin.HoaDon.index', compact(
            'hoaDons',
            'tongHoaDon',
            'tongDoanhThu',
            'hoaDonThangNay',
            'doanhThuThangNay'
        ));
    }

    /**
     * Hiển thị form tạo hóa đơn mới
     * - Lấy danh sách đặt phòng chưa có hóa đơn
     *   (trạng thái: Đã duyệt=2, Đã xác nhận=4, Đang ở=5, Đã trả phòng=6)
     * - Truyền danh sách phòng để hiển thị trong form
     */
    public function create()
    {
        // Lấy danh sách đặt phòng hợp lệ (đã duyệt, xác nhận, đang ở, trả phòng)
        // và chưa có hóa đơn nào được tạo
        $datPhongs = DatPhong::with(['phong', 'khachHang', 'trangThaiDatPhong'])
            ->whereIn('MaTrangThaiDP', [2, 4, 5, 6]) // Chỉ lấy trạng thái hợp lệ
            ->whereDoesntHave('hoaDon')               // Chưa có hóa đơn
            ->orderBy('MaDatPhong', 'desc')
            ->get();

        // Danh sách phòng (để hiển thị bổ sung nếu cần)
        $phongs = Phong::all();

        return view('admin.HoaDon.create', compact('datPhongs', 'phongs'));
    }

    /**
     * Lưu hóa đơn mới vào database
     * - Tính TongTien = GiaPhong × Số đêm (tối thiểu 1 đêm)
     * - Gán NgayLapHD = ngày hiện tại
     */
    public function store(Request $request)
    {
        // Validate dữ liệu đầu vào
        $request->validate([
            'MaDatPhong' => 'required|exists:dat_phong,MaDatPhong', // Phải chọn đặt phòng hợp lệ
        ], [
            'MaDatPhong.required' => 'Vui lòng chọn đặt phòng để tạo hóa đơn',
            'MaDatPhong.exists' => 'Đặt phòng không tồn tại',
        ]);

        // Lấy thông tin đặt phòng kèm phòng
        $datPhong = DatPhong::with('phong')->findOrFail($request->MaDatPhong);

        // Kiểm tra đã có hóa đơn cho đặt phòng này chưa
        $daCoHoaDon = HoaDon::where('MaDatPhong', $datPhong->MaDatPhong)->exists();
        if ($daCoHoaDon) {
            return redirect()->route('admin.hoa-don.create')
                ->with('error', 'Đặt phòng này đã có hóa đơn rồi!');
        }

        // Tính số đêm (tối thiểu 1 đêm)
        $ngayDat = Carbon::parse($datPhong->NgayDatPhong);
        $ngayTra = Carbon::parse($datPhong->NgayTraPhong);
        $soNgay = max(1, $ngayDat->diffInDays($ngayTra));

        // Tính tổng tiền = giá phòng × số đêm
        $tongTien = $datPhong->phong ? $datPhong->phong->GiaPhong * $soNgay : 0;

        // Tạo hóa đơn mới
        HoaDon::create([
            'MaPhong'    => $datPhong->MaPhong,         // Mã phòng từ đặt phòng
            'MaDatPhong' => $datPhong->MaDatPhong,       // Mã đặt phòng
            'NgayLapHD'  => Carbon::now(),                // Ngày lập = hôm nay
            'TongTien'   => $tongTien,                    // Tổng tiền đã tính
        ]);

        return redirect()->route('admin.hoa-don.index')
            ->with('success', 'Tạo hóa đơn thành công! Tổng tiền: ' . number_format($tongTien, 0, ',', '.') . 'đ');
    }

    /**
     * Hiển thị chi tiết 1 hóa đơn
     * - Load thêm thông tin: phòng, loại phòng, đặt phòng, khách hàng, trạng thái
     */
    public function show($id)
    {
        // Tìm hóa đơn theo mã, kèm tất cả quan hệ cần thiết
        $hoaDon = HoaDon::with([
            'phong.loaiPhong',                    // Phòng + loại phòng
            'datPhong.khachHang',                 // Đặt phòng + khách hàng
            'datPhong.trangThaiDatPhong',         // Trạng thái đặt phòng
        ])->findOrFail($id);

        // Tính số đêm để hiển thị
        $soNgay = 0;
        if ($hoaDon->datPhong) {
            $ngayDat = Carbon::parse($hoaDon->datPhong->NgayDatPhong);
            $ngayTra = Carbon::parse($hoaDon->datPhong->NgayTraPhong);
            $soNgay = max(1, $ngayDat->diffInDays($ngayTra));
        }

        return view('admin.HoaDon.show', compact('hoaDon', 'soNgay'));
    }

    /**
     * Xóa hóa đơn
     * - Xóa bản ghi hóa đơn khỏi database
     */
    public function destroy($id)
    {
        // Tìm hóa đơn theo mã
        $hoaDon = HoaDon::findOrFail($id);

        // Xóa hóa đơn
        $hoaDon->delete();

        return redirect()->route('admin.hoa-don.index')
            ->with('success', 'Xóa hóa đơn thành công!');
    }
}
