<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\BaseController;
use App\Models\Phong;
use App\Models\DatPhong;
use App\Models\KhachHang;
use App\Models\HoaDon;
use App\Models\LoaiPhong;
use App\Models\TrangThaiPhong;
use Carbon\Carbon;

class DashboardController extends BaseController
{
    /**
     * Hiển thị trang dashboard
     */
    public function index()
    {
        // Thống kê tổng quan
        $tongPhong = Phong::count();
        $tongKhachHang = KhachHang::count();
        $tongLoaiPhong = LoaiPhong::count();
        $tongTrangThai = TrangThaiPhong::count();

        // Đặt phòng hôm nay
        $tongDatPhong = DatPhong::whereDate('NgayDatPhong', Carbon::today())->count();

        // Doanh thu tháng này
        $doanhThu = HoaDon::whereMonth('NgayLapHD', Carbon::now()->month)
            ->whereYear('NgayLapHD', Carbon::now()->year)
            ->sum('TongTien');

        // Thống kê trạng thái phòng
        $phongTrong = 0;
        $phongDangSuDung = 0;
        $phongBaoTri = 0;
        $phongDonDep = 0;

        $phongs = Phong::with('trangThaiPhong')->get();
        foreach ($phongs as $phong) {
            if ($phong->trangThaiPhong) {
                $tenTT = mb_strtolower($phong->trangThaiPhong->TenTrangThai);
                if (str_contains($tenTT, 'trống') || str_contains($tenTT, 'trong')) {
                    $phongTrong++;
                } elseif (str_contains($tenTT, 'sử dụng') || str_contains($tenTT, 'có khách') || str_contains($tenTT, 'đang ở')) {
                    $phongDangSuDung++;
                } elseif (str_contains($tenTT, 'bảo trì') || str_contains($tenTT, 'sửa chữa')) {
                    $phongBaoTri++;
                } elseif (str_contains($tenTT, 'dọn dẹp') || str_contains($tenTT, 'vệ sinh')) {
                    $phongDonDep++;
                } else {
                    $phongTrong++; // Mặc định là trống nếu không xác định
                }
            } else {
                $phongTrong++; // Nếu không có trạng thái, coi như trống
            }
        }

        // Đặt phòng gần đây
        $datPhongGanDay = DatPhong::with(['phong', 'khachHang', 'trangThaiDatPhong'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Thống kê so sánh với tháng trước
        $doanhThuThangTruoc = HoaDon::whereMonth('NgayLapHD', Carbon::now()->subMonth()->month)
            ->whereYear('NgayLapHD', Carbon::now()->subMonth()->year)
            ->sum('TongTien');

        $phanTramDoanhThu = $doanhThuThangTruoc > 0 
            ? round((($doanhThu - $doanhThuThangTruoc) / $doanhThuThangTruoc) * 100, 1) 
            : 0;

        // Khách hàng mới tuần này
        $khachHangMoiTuan = KhachHang::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $khachHangTuanTruoc = KhachHang::whereBetween('created_at', [
            Carbon::now()->subWeeks(2),
            Carbon::now()->subWeek()
        ])->count();
        $phanTramKhachHang = $khachHangTuanTruoc > 0 
            ? round((($khachHangMoiTuan - $khachHangTuanTruoc) / $khachHangTuanTruoc) * 100, 1) 
            : 0;

        return view('admin.dashboard', compact(
            'tongPhong',
            'tongDatPhong',
            'tongKhachHang',
            'doanhThu',
            'phongTrong',
            'phongDangSuDung',
            'phongBaoTri',
            'phongDonDep',
            'datPhongGanDay',
            'tongLoaiPhong',
            'tongTrangThai',
            'phanTramDoanhThu',
            'phanTramKhachHang'
        ));
    }
}
