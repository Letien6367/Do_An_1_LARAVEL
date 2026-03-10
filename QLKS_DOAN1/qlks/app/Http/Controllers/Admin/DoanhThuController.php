<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\DatPhong;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DoanhThuController extends BaseController
{
    /**
     * Các trạng thái đặt phòng được tính doanh thu:
     * 2 = Đã duyệt
     * 4 = Đã xác nhận
     * 5 = Đang ở
     * 6 = Đã trả phòng
     */
    private $trangThaiHopLe = [2, 4, 5, 6];

    /**
     * Tính tiền 1 đặt phòng = Giá phòng × Số đêm (tối thiểu 1 đêm)
     */
    private function tinhTien($dp)
    {
        // Nếu phòng không tồn tại thì trả về 0
        if (!$dp->phong) return 0;

        // Tính số đêm giữa ngày đặt và ngày trả
        $soNgay = max(1, Carbon::parse($dp->NgayDatPhong)->diffInDays(Carbon::parse($dp->NgayTraPhong)));

        return $dp->phong->GiaPhong * $soNgay;
    }

    /**
     * Tạo query cơ sở: lấy đặt phòng hợp lệ kèm quan hệ
     */
    private function queryHopLe()
    {
        return DatPhong::with(['phong', 'khachHang', 'trangThaiDatPhong'])
            ->whereIn('MaTrangThaiDP', $this->trangThaiHopLe);
    }

    /**
     * Tính tổng doanh thu từ danh sách đặt phòng
     */
    private function tongDoanhThu($danhSach)
    {
        return $danhSach->sum(fn($dp) => $this->tinhTien($dp));
    }

    /**
     * Trang thống kê doanh thu
     *
     * Gồm 3 phần:
     * 1. Thống kê tổng quan (hôm nay, tháng này, năm nay, tổng đặt phòng)
     * 2. Bộ lọc theo năm / tháng
     * 3. Bảng chi tiết đặt phòng (có phân trang)
     */
    public function index(Request $request)
    {
        // ========================================
        // PHẦN 1: LẤY THAM SỐ LỌC TỪ URL
        // ========================================
        $now   = Carbon::now();
        $nam   = (int) $request->get('nam', $now->year);   // Năm được chọn (mặc định: năm hiện tại)
        $thang = $request->get('thang', null);              // Tháng được chọn (mặc định: tất cả)

        // ========================================
        // PHẦN 2: THỐNG KÊ TỔNG QUAN (4 ô trên cùng)
        // ========================================

        // Doanh thu hôm nay
        $doanhThuHomNay = $this->tongDoanhThu(
            $this->queryHopLe()->whereDate('NgayDatPhong', $now->toDateString())->get()
        );

        // Doanh thu tháng này
        $doanhThuThangNay = $this->tongDoanhThu(
            $this->queryHopLe()
                ->whereMonth('NgayDatPhong', $now->month)
                ->whereYear('NgayDatPhong', $now->year)
                ->get()
        );

        // Doanh thu năm này
        $doanhThuNamNay = $this->tongDoanhThu(
            $this->queryHopLe()->whereYear('NgayDatPhong', $now->year)->get()
        );

        // Tổng số đặt phòng hợp lệ (tất cả thời gian)
        $tongDatPhong = $this->queryHopLe()->count();

        // ========================================
        // PHẦN 3: DANH SÁCH ĐẶT PHÒNG THEO BỘ LỌC
        // ========================================

        // Tạo query lọc theo năm (và tháng nếu có)
        $queryLoc = $this->queryHopLe()->whereYear('NgayDatPhong', $nam);
        if ($thang) {
            $queryLoc->whereMonth('NgayDatPhong', (int) $thang);
        }

        // Lấy tất cả để tính tổng doanh thu theo bộ lọc
        $tatCaDatPhongLoc = (clone $queryLoc)->get();
        $tongDoanhThuLoc  = $this->tongDoanhThu($tatCaDatPhongLoc);
        $tongDatPhongLoc  = $tatCaDatPhongLoc->count();

        // Phân trang cho bảng hiển thị (10 dòng/trang)
        $datPhongs = $queryLoc->orderBy('NgayDatPhong', 'desc')->paginate(10);

        // Danh sách năm cho dropdown lọc (từ năm hiện tại lùi 5 năm)
        $danhSachNam = range($now->year, $now->year - 5);

        // ========================================
        // PHẦN 4: TRẢ DỮ LIỆU VỀ VIEW
        // ========================================
        return view('admin.DoanhThu.index', compact(
            'doanhThuHomNay',   // Số tiền doanh thu hôm nay
            'doanhThuThangNay', // Số tiền doanh thu tháng này
            'doanhThuNamNay',   // Số tiền doanh thu năm này
            'tongDatPhong',     // Tổng đặt phòng hợp lệ
            'datPhongs',        // Danh sách đặt phòng (phân trang)
            'nam',              // Năm đang lọc
            'thang',            // Tháng đang lọc
            'tongDoanhThuLoc',  // Tổng doanh thu theo bộ lọc
            'tongDatPhongLoc',  // Tổng đặt phòng theo bộ lọc
            'danhSachNam'       // Danh sách năm cho dropdown
        ));
    }
}
