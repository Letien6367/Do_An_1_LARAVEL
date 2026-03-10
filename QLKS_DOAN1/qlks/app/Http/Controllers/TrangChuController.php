<?php

/**
 * ===================================================================
 * TrangChuController - Controller trang chủ
 * ===================================================================
 * Controller hiển thị trang chủ với danh sách phòng
 * Hỗ trợ tìm kiếm phòng theo:
 *   - Loại phòng (VIP, Thường, Deluxe, ...)
 *   - Trạng thái phòng (Trống, Đã đặt, Đang sửa, ...)
 *   - Từ khóa (tên phòng)
 */

namespace App\Http\Controllers;

// Import các Model cần thiết
use App\Models\LoaiPhong;         // Model loại phòng (VIP, Standard, Deluxe, ...)
use App\Models\Phong;             // Model phòng
use App\Models\TrangThaiPhong;    // Model trạng thái phòng (Trống, Đã đặt, ...)
use Illuminate\Http\Request;      // Class xử lý HTTP Request

class TrangChuController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * Phương thức: index (GET)
     * ---------------------------------------------------------------
     * Mục đích: Hiển thị trang chủ website khách sạn
     * 
     * Hỗ trợ lọc/tìm kiếm phòng qua các tham số GET:
     *   - loai_phong: Lọc theo mã loại phòng (MaLoaiPhong)
     *   - trang_thai: Lọc theo mã trạng thái phòng (MaTrangThai)
     *   - tu_khoa:    Tìm kiếm theo tên phòng
     * 
     * Route: GET / (trangchu)
     */
    public function index(Request $request)
    {
        // --- Lấy danh sách loại phòng và trạng thái phòng (dùng cho dropdown lọc) ---
        $loaiPhongs = LoaiPhong::all();              // Tất cả loại phòng
        $trangThaiPhongs = TrangThaiPhong::all();    // Tất cả trạng thái phòng

        // --- Khởi tạo truy vấn phòng kèm eager loading ---
        // with(): lấy sẵn dữ liệu loại phòng + trạng thái phòng (tránh N+1 query)
        $query = Phong::with(['loaiPhong', 'trangThaiPhong']);

        // --- Lọc theo loại phòng (nếu có chọn) ---
        // VD: Chỉ hiển thị phòng VIP, hoặc chỉ phòng Thường
        if ($request->filled('loai_phong')) {
            $query->where('MaLoaiPhong', $request->loai_phong);
        }

        // --- Lọc theo trạng thái phòng (nếu có chọn) ---
        // VD: Chỉ hiển thị phòng Trống, hoặc phòng Đã đặt
        if ($request->filled('trang_thai')) {
            $query->where('MaTrangThai', $request->trang_thai);
        }

        // --- Tìm kiếm theo tên phòng (nếu có nhập) ---
        // VD: Nhập "101" → tìm phòng có tên chứa "101"
        if ($request->filled('tu_khoa')) {
            $query->where('TenPhong', 'like', '%' . $request->tu_khoa . '%');
        }

        // --- Kiểm tra xem có đang lọc/tìm kiếm không ---
        // Nếu có → hiển thị TẤT CẢ kết quả phù hợp
        // Nếu không → chỉ hiển thị 6 phòng mới nhất (mặc định trên trang chủ)
        $dangTimKiem = $request->hasAny(['loai_phong', 'trang_thai', 'tu_khoa']) 
                       && ($request->filled('loai_phong') || $request->filled('trang_thai') || $request->filled('tu_khoa'));

        if ($dangTimKiem) {
            // Đang tìm kiếm → lấy tất cả kết quả phù hợp
            $phongs = $query->orderBy('MaPhong', 'desc')->get();
        } else {
            // Không tìm kiếm → chỉ lấy 6 phòng mới nhất
            $phongs = $query->orderBy('MaPhong', 'desc')->take(6)->get();
        }

        // Trả về view trang chủ kèm dữ liệu
        return view('KhachHang.TrangChu', compact(
            'loaiPhongs',       // Danh sách loại phòng (cho dropdown)
            'trangThaiPhongs',  // Danh sách trạng thái phòng (cho dropdown)
            'phongs',           // Danh sách phòng (kết quả hiển thị)
            'dangTimKiem'       // Cờ đánh dấu: đang tìm kiếm hay không
        ));
    }
}
