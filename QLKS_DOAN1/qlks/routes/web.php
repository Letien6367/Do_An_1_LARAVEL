<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\DatPhongController;
use App\Http\Controllers\Admin\PhongController;
use App\Http\Controllers\Admin\LoaiPhongController;
use App\Http\Controllers\Admin\TrangThaiPhongController;
use App\Http\Controllers\Admin\TrangThaiDatPhongController;
use App\Http\Controllers\Admin\DatPhongController as AdminDatPhongController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KhachHangController;
use App\Http\Controllers\Admin\DoanhThuController;
use App\Http\Controllers\Admin\HoaDonController;
use App\Http\Controllers\Admin\UserController;

// Trang chủ người dùng
Route::get('/', [TrangChuController::class, 'index'])->name('trangchu');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// =============================================
// ĐẶT PHÒNG PHÍA KHÁCH HÀNG (không cần đăng nhập admin)
// =============================================
// Dùng DatPhongController (không phải AdminDatPhongController)
// Đây là các route cho KHÁCH HÀNG thao tác đặt phòng trên giao diện người dùng

// 1) Hiển thị form đặt phòng
//    - URL: /dat-phong/5  (5 là MaPhong)
//    - {maPhong} là tham số truyền vào, chính là mã phòng khách muốn đặt
//    - Khi khách bấm "Đặt phòng" ở trang chủ => chuyển đến form này
Route::get('/dat-phong/{maPhong}', [DatPhongController::class, 'create'])->name('dat-phong.create');

// 2) Xử lý lưu đặt phòng
//    - Method POST: nhận dữ liệu từ form (ngày đặt, ngày trả...)
//    - Controller sẽ tạo bản ghi mới trong bảng dat_phong
//    - Sau khi lưu thành công => redirect sang trang thanh-cong
Route::post('/dat-phong', [DatPhongController::class, 'store'])->name('dat-phong.store');

// 3) Trang thông báo đặt phòng thành công
//    - URL: /dat-phong/thanh-cong/10  (10 là MaDatPhong vừa tạo)
//    - Hiển thị thông tin xác nhận đặt phòng cho khách
Route::get('/dat-phong/thanh-cong/{maDatPhong}', [DatPhongController::class, 'thanhCong'])->name('dat-phong.thanh-cong');

// 4) Xem lịch sử đặt phòng của khách hàng
//    - URL: /lich-su-dat-phong
//    - Hiển thị danh sách các lần đặt phòng của khách đang đăng nhập
Route::get('/lich-su-dat-phong', [DatPhongController::class, 'lichSu'])->name('dat-phong.lich-su');

// 5) Hủy đặt phòng
//    - Method DELETE: xóa/hủy đặt phòng theo MaDatPhong
//    - URL: /dat-phong/huy/10  (10 là MaDatPhong cần hủy)
//    - Khách chỉ được hủy đặt phòng của chính mình
Route::delete('/dat-phong/huy/{maDatPhong}', [DatPhongController::class, 'huy'])->name('dat-phong.huy');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Quản lý phòng
    Route::resource('phong', PhongController::class);

    // Quản lý loại phòng
    Route::resource('loai-phong', LoaiPhongController::class);

    // Quản lý trạng thái phòng
    Route::resource('trang-thai-phong', TrangThaiPhongController::class);

    // Quản lý trạng thái đặt phòng
    Route::resource('trang-thai-dat-phong', TrangThaiDatPhongController::class);

    // Quản lý đặt phòng
    Route::resource('dat-phong', AdminDatPhongController::class);

    // Thao tác nhanh trên danh sách đặt phòng: xác nhận hoặc hủy đơn
    Route::post('dat-phong/{id}/xac-nhan', [AdminDatPhongController::class, 'xacNhan'])->name('dat-phong.xac-nhan');
    Route::post('dat-phong/{id}/huy', [AdminDatPhongController::class, 'huy'])->name('dat-phong.huy');

    // Quản lý khách hàng
    Route::resource('khach-hang', KhachHangController::class);

    // Quản lý hóa đơn
    Route::resource('hoa-don', HoaDonController::class)->except(['edit', 'update']);

    // Quản lý tài khoản người dùng
    Route::resource('users', UserController::class);

    // Doanh thu
    Route::get('doanh-thu', [DoanhThuController::class, 'index'])->name('doanh-thu.index');
});
