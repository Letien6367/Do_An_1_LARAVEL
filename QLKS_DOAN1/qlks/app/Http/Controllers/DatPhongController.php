<?php

/**
 * ===================================================================
 * DatPhongController - Controller xử lý đặt phòng
 * ===================================================================
 * Controller này chịu trách nhiệm cho toàn bộ quy trình đặt phòng:
 * - Hiển thị form đặt phòng (create)
 * - Xử lý lưu đơn đặt phòng (store)
 * - Hiển thị trang đặt phòng thành công (thanhCong)
 * - Hiển thị lịch sử đặt phòng (lichSu)
 * - Hủy đơn đặt phòng (huy)
 */

namespace App\Http\Controllers;

// Import các Model cần thiết
use App\Models\DatPhong;           // Model đơn đặt phòng
use App\Models\Phong;              // Model phòng
use App\Models\KhachHang;          // Model khách hàng
use App\Models\TrangThaiDatPhong;  // Model trạng thái đơn đặt phòng
use Illuminate\Http\Request;       // Class xử lý HTTP Request
use Illuminate\Support\Facades\Auth; // Facade xác thực người dùng

class DatPhongController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * Phương thức: create (GET)
     * ---------------------------------------------------------------
     * Mục đích: Hiển thị form đặt phòng cho khách hàng
     * Tham số: $maPhong - mã phòng mà khách muốn đặt
     * Trả về: View 'KhachHang.DatPhong' với dữ liệu phòng, khách hàng, user
     */
    public function create($maPhong)
    {
        // Lấy thông tin phòng từ database dựa vào mã phòng
        $phong = Phong::with(['loaiPhong', 'trangThaiPhong'])->findOrFail($maPhong);

        // Nếu đã đăng nhập → lấy thông tin khách hàng liên kết
        $user = Auth::user();
        $khachHang = null;
        if ($user) {
            $khachHang = KhachHang::where('MaTaiKhoan', $user->id)->first();
        }

        return view('KhachHang.DatPhong', compact('phong', 'khachHang', 'user'));
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: store (POST)
     * ---------------------------------------------------------------
     * Mục đích: Xử lý dữ liệu form đặt phòng và lưu vào database
     * Tham số: $request - chứa toàn bộ dữ liệu từ form gửi lên
     * Quy trình:
     *   1. Kiểm tra đăng nhập
     *   2. Validate dữ liệu form
     *   3. Tạo/cập nhật hồ sơ khách hàng
     *   4. Kiểm tra phòng có trống trong khoảng thời gian đặt
     *   5. Tạo đơn đặt phòng mới
     *   6. Chuyển hướng đến trang thành công
     */
    public function store(Request $request)
    {
        // Validate (kiểm tra hợp lệ) dữ liệu form
        // - required: trường bắt buộc nhập
        // - exists:phong,MaPhong: MaPhong phải tồn tại trong bảng phong
        // - before:today: ngày sinh phải trước ngày hôm nay
        // - after_or_equal:today: ngày nhận phòng từ hôm nay trở đi
        // - after:NgayDatPhong: ngày trả phòng phải sau ngày nhận
        $request->validate([
            'MaPhong'        => 'required|exists:phong,MaPhong',
            'TenKhachHang'   => 'required|string|max:255',
            'SoDienThoai'    => 'required|string|max:20',
            'NgaySinh'       => 'required|date|before:today',
            'GiayChungMinh'  => 'required|string|max:20',
            'DiaChi'         => 'required|string|max:255',
            'NgayDatPhong'   => 'required|date|after_or_equal:today',
            'NgayTraPhong'   => 'required|date|after:NgayDatPhong',
        ], [
            // Mảng thông báo lỗi tùy chỉnh bằng tiếng Việt
            'MaPhong.required'            => 'Vui lòng chọn phòng.',
            'MaPhong.exists'              => 'Phòng không tồn tại.',
            'TenKhachHang.required'       => 'Vui lòng nhập họ tên khách hàng.',
            'SoDienThoai.required'        => 'Vui lòng nhập số điện thoại.',
            'NgaySinh.required'           => 'Vui lòng nhập ngày sinh.',
            'NgaySinh.before'             => 'Ngày sinh phải trước ngày hôm nay.',
            'GiayChungMinh.required'      => 'Vui lòng nhập số CMND/CCCD.',
            'DiaChi.required'             => 'Vui lòng nhập địa chỉ.',
            'NgayDatPhong.required'       => 'Vui lòng chọn ngày nhận phòng.',
            'NgayDatPhong.date'           => 'Ngày nhận phòng không hợp lệ.',
            'NgayDatPhong.after_or_equal' => 'Ngày nhận phòng phải từ hôm nay trở đi.',
            'NgayTraPhong.required'       => 'Vui lòng chọn ngày trả phòng.',
            'NgayTraPhong.date'           => 'Ngày trả phòng không hợp lệ.',
            'NgayTraPhong.after'          => 'Ngày trả phòng phải sau ngày nhận phòng.',
        ]);

        // Lấy thông tin user (có thể null nếu khách vãng lai)
        $user = Auth::user();
        
        // Chuẩn bị dữ liệu khách hàng từ form
        $khachHangData = [
            'TenKhachHang'  => $request->TenKhachHang,
            'NgaySinh'      => $request->NgaySinh,
            'SoDienThoai'   => $request->SoDienThoai,
            'DiaChi'        => $request->DiaChi,
            'GiayChungMinh' => $request->GiayChungMinh,
        ];

        if ($user) {
            // Người dùng đã đăng nhập → tìm hoặc tạo hồ sơ khách hàng liên kết
            $khachHang = KhachHang::where('MaTaiKhoan', $user->id)->first();
            if (!$khachHang) {
                $khachHangData['MaTaiKhoan'] = $user->id;
                $khachHang = KhachHang::create($khachHangData);
            } else {
                $khachHang->update($khachHangData);
            }
        } else {
            // Khách vãng lai → tạo hồ sơ khách hàng mới (không liên kết tài khoản)
            $khachHang = KhachHang::create($khachHangData);
        }

        // Bước 5: Lấy mã trạng thái "Chờ duyệt" từ bảng trang_thai_dat_phong
        // Đơn đặt phòng mới luôn ở trạng thái "Chờ duyệt" (chờ lễ tân xác nhận)
        $trangThaiChoDuyet = TrangThaiDatPhong::where('TenTrangThaiDP', 'Chờ duyệt')->first();
        $maTrangThai = $trangThaiChoDuyet ? $trangThaiChoDuyet->MaTrangThaiDP : 1; // Mặc định = 1

        // Bước 6: Lấy danh sách trạng thái không còn hiệu lực
        // Các đơn đã hủy hoặc đã trả phòng không ảnh hưởng đến lịch đặt phòng
        $trangThaiKhongHieuLuc = TrangThaiDatPhong::whereIn('TenTrangThaiDP', ['Đã hủy', 'Đã trả phòng'])
            ->pluck('MaTrangThaiDP')  // Chỉ lấy cột MaTrangThaiDP
            ->toArray();              // Chuyển thành mảng PHP

        // Bước 7: Kiểm tra xung đột lịch đặt phòng
        // ---------------------------------------------------------------
        // Logic kiểm tra: 2 khoảng thời gian [A_start, A_end] và [B_start, B_end]
        // bị trùng nhau khi và chỉ khi: A_start < B_end VÀ A_end > B_start
        //
        // Ví dụ: Đơn cũ [01/03, 05/03], Đơn mới [03/03, 07/03]
        //   → 01/03 < 07/03 ✓ VÀ 05/03 > 03/03 ✓ → BỊ TRÙNG!
        //
        // Ví dụ: Đơn cũ [01/03, 05/03], Đơn mới [06/03, 10/03]
        //   → 01/03 < 10/03 ✓ VÀ 05/03 > 06/03 ✗ → KHÔNG TRÙNG ✓
        // ---------------------------------------------------------------
        $phongDaDat = DatPhong::where('MaPhong', $request->MaPhong)
            ->whereNotIn('MaTrangThaiDP', $trangThaiKhongHieuLuc) // Bỏ qua đơn đã hủy/đã trả
            ->where('NgayDatPhong', '<', $request->NgayTraPhong)   // Đơn cũ bắt đầu trước khi đơn mới kết thúc
            ->where('NgayTraPhong', '>', $request->NgayDatPhong)   // Đơn cũ kết thúc sau khi đơn mới bắt đầu
            ->exists(); // Trả về true/false (có tồn tại hay không)

        // Nếu phòng đã được đặt → quay lại form với thông báo lỗi
        if ($phongDaDat) {
            return back()->withInput()->with('error', 'Phòng đã được đặt trong khoảng thời gian này. Vui lòng chọn ngày khác.');
        }

        // Bước 8: Tạo đơn đặt phòng mới trong bảng dat_phong
        $datPhong = DatPhong::create([
            'MaPhong'       => $request->MaPhong,         // Mã phòng được đặt
            'MaKhachHang'   => $khachHang->MaKhachHang,   // Mã khách hàng (vừa tạo/cập nhật)
            'NgayDatPhong'  => $request->NgayDatPhong,     // Ngày nhận phòng
            'NgayTraPhong'  => $request->NgayTraPhong,     // Ngày trả phòng
            'MaTrangThaiDP' => $maTrangThai,               // Trạng thái = "Chờ duyệt"
        ]);

        // Lưu mã đặt phòng vào session để khách vãng lai có thể xem trang thành công
        session(['dat_phong_thanh_cong' => $datPhong->MaDatPhong]);

        return redirect()->route('dat-phong.thanh-cong', $datPhong->MaDatPhong)
            ->with('success', 'Đặt phòng thành công! Đơn đặt phòng của bạn đang chờ duyệt.');
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: thanhCong (GET)
     * ---------------------------------------------------------------
     * Mục đích: Hiển thị trang thông báo đặt phòng thành công
     * Tham số: $maDatPhong - mã đơn đặt phòng vừa tạo
     * Bảo mật: Chỉ cho phép chủ sở hữu đơn xem thông tin
     */
    public function thanhCong($maDatPhong)
    {
        $datPhong = DatPhong::with(['phong.loaiPhong', 'khachHang', 'trangThaiDatPhong'])
            ->findOrFail($maDatPhong);

        if (Auth::check()) {
            // Người dùng đã đăng nhập → kiểm tra quyền sở hữu
            $user = Auth::user();
            $khachHang = KhachHang::where('MaTaiKhoan', $user->id)->first();
            if (!$khachHang || $datPhong->MaKhachHang != $khachHang->MaKhachHang) {
                abort(403, 'Bạn không có quyền xem đơn đặt phòng này.');
            }
        } else {
            // Khách vãng lai → chỉ cho xem nếu mã đặt phòng khớp với session
            if (session('dat_phong_thanh_cong') != $maDatPhong) {
                abort(403, 'Bạn không có quyền xem đơn đặt phòng này.');
            }
        }

        return view('KhachHang.DatPhongThanhCong', compact('datPhong'));
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: lichSu (GET)
     * ---------------------------------------------------------------
     * Mục đích: Hiển thị danh sách tất cả đơn đặt phòng của khách hàng
     * Tính năng: Sắp xếp mới nhất lên trước, phân trang 10 đơn/trang
     */
    public function lichSu()
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để xem lịch sử đặt phòng.');
        }

        // Lấy thông tin user và tìm hồ sơ khách hàng tương ứng
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTaiKhoan', $user->id)->first();

        // Khởi tạo danh sách rỗng (trường hợp chưa có hồ sơ khách hàng)
        $datPhongs = [];

        if ($khachHang) {
            // Lấy tất cả đơn đặt phòng của khách hàng
            // - with(): Eager loading các quan hệ (phòng, loại phòng, trạng thái)
            // - orderBy('created_at', 'desc'): Sắp xếp mới nhất lên trước
            // - paginate(10): Phân trang, mỗi trang 10 đơn
            $datPhongs = DatPhong::with(['phong.loaiPhong', 'trangThaiDatPhong'])
                ->where('MaKhachHang', $khachHang->MaKhachHang)
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        // Trả về view lịch sử đặt phòng
        return view('KhachHang.LichSuDatPhong', compact('datPhongs'));
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: huy (DELETE)
     * ---------------------------------------------------------------
     * Mục đích: Hủy một đơn đặt phòng
     * Tham số: $maDatPhong - mã đơn cần hủy
     * Điều kiện: Chỉ hủy được khi đơn ở trạng thái "Chờ duyệt" (1) hoặc "Đã duyệt" (2)
     * Bảo mật: Chỉ chủ đơn mới được hủy
     */
    public function huy($maDatPhong)
    {
        // Kiểm tra đăng nhập
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Tìm đơn đặt phòng theo mã (404 nếu không tìm thấy)
        $datPhong = DatPhong::findOrFail($maDatPhong);
        
        // Kiểm tra quyền: chỉ chủ đơn mới được hủy
        $user = Auth::user();
        $khachHang = KhachHang::where('MaTaiKhoan', $user->id)->first();
        
        // Nếu không phải chủ đơn → lỗi 403 (Forbidden)
        if (!$khachHang || $datPhong->MaKhachHang != $khachHang->MaKhachHang) {
            abort(403, 'Bạn không có quyền hủy đơn đặt phòng này.');
        }

        // Kiểm tra trạng thái: chỉ cho phép hủy khi đang "Chờ duyệt" (1) hoặc "Đã duyệt" (2)
        // Không thể hủy nếu đã ở trạng thái "Đang ở", "Đã trả phòng" hoặc "Đã hủy"
        if (!in_array($datPhong->MaTrangThaiDP, [1, 2])) {
            return back()->with('error', 'Không thể hủy đơn đặt phòng này.');
        }

        // Cập nhật trạng thái đơn thành "Đã hủy"
        // Tìm mã trạng thái "Đã hủy" từ database, mặc định = 7 nếu không tìm thấy
        $trangThaiHuy = TrangThaiDatPhong::where('TenTrangThaiDP', 'Đã hủy')->first();
        $datPhong->MaTrangThaiDP = $trangThaiHuy ? $trangThaiHuy->MaTrangThaiDP : 7;
        $datPhong->save(); // Lưu thay đổi vào database

        // Quay lại trang trước kèm thông báo thành công
        return back()->with('success', 'Đã hủy đơn đặt phòng thành công.');
    }
}
