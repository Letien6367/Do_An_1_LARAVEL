<?php

namespace App\Http\Controllers\Admin;

// === Import các thư viện cần thiết ===
use App\Http\Controllers\Controller;             // Controller gốc của Laravel
use App\Models\User;                             // Model User (bảng users)
use Illuminate\Http\Request;                     // Xử lý dữ liệu từ form gửi lên
use Illuminate\Support\Facades\Auth;             // Lấy thông tin người dùng đang đăng nhập
use Illuminate\Support\Facades\Hash;             // Mã hóa mật khẩu
use Illuminate\Validation\Rule;                  // Quy tắc validate nâng cao

/**
 * ===================================================================
 * CONTROLLER QUẢN LÝ NGƯỜI DÙNG (USERS)
 * ===================================================================
 * CHỈ TÀI KHOẢN CÓ VAI TRÒ "admin" MỚI ĐƯỢC TRUY CẬP
 * 
 * Chức năng:
 * - Hiển thị danh sách tài khoản người dùng
 * - Thêm, sửa, xóa tài khoản
 * - Tìm kiếm theo tên, email, SĐT
 * - Lọc theo vai trò (admin, letan, KhachHang)
 */
class UserController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * CONSTRUCTOR: KIỂM TRA QUYỀN ADMIN
     * ---------------------------------------------------------------
     * Hàm này chạy TRƯỚC tất cả các phương thức trong controller
     * Nếu không phải admin → đá về trang chủ, không cho vào
     */
    public function __construct()
    {
        // Kiểm tra: người dùng phải đăng nhập VÀ có vai trò 'admin'
        // Nếu không phải admin → chuyển về trang chủ kèm thông báo lỗi
        if (!Auth::check() || Auth::user()->VaiTro !== 'admin') {
            abort(403, 'Bạn không có quyền truy cập chức năng này. Chỉ Admin mới được phép!');
        }
    }

    /**
     * ---------------------------------------------------------------
     * DANH SÁCH NGƯỜI DÙNG (GET /admin/users)
     * ---------------------------------------------------------------
     * - Hiển thị tất cả tài khoản người dùng
     * - Hỗ trợ tìm kiếm theo tên, email, số điện thoại
     * - Hỗ trợ lọc theo vai trò
     * - Phân trang mỗi trang 10 bản ghi
     */
    public function index(Request $request)
    {
        // Khởi tạo truy vấn lấy danh sách users
        $query = User::query();

        // --- Tìm kiếm theo từ khóa (tên, email, SĐT) ---
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")        // Tìm theo tên
                  ->orWhere('email', 'like', "%{$search}%")      // Tìm theo email
                  ->orWhere('SoDienThoai', 'like', "%{$search}%"); // Tìm theo SĐT
            });
        }

        // --- Lọc theo vai trò (admin / letan / KhachHang) ---
        if ($request->has('vai_tro') && $request->vai_tro != '') {
            if (in_array($request->vai_tro, ['KhachHang', 'user'], true)) {
                // Tương thích dữ liệu cũ: một số bản ghi cũ dùng "user".
                $query->whereIn('VaiTro', ['KhachHang', 'user']);
            } else {
                $query->where('VaiTro', $request->vai_tro);
            }
        }

        // Sắp xếp mới nhất lên đầu, phân trang 10 bản ghi/trang
        $users = $query->orderBy('id', 'desc')->paginate(10);

        // --- Thống kê số lượng ---
        $tongUser = User::count();                                          // Tổng tất cả user
        $soAdmin = User::where('VaiTro', 'admin')->count();                 // Số admin
        $soLeTan = User::where('VaiTro', 'letan')->count();                 // Số lễ tân
        $soKhachHang = User::whereIn('VaiTro', ['KhachHang', 'user'])->count(); // Số khách hàng

        // Trả về view danh sách kèm theo dữ liệu
        return view('admin.Users.index', compact(
            'users', 'tongUser', 'soAdmin', 'soLeTan', 'soKhachHang'
        ));
    }

    /**
     * ---------------------------------------------------------------
     * FORM THÊM MỚI (GET /admin/users/create)
     * ---------------------------------------------------------------
     * Hiển thị form để nhập thông tin người dùng mới
     */
    public function create()
    {
        return view('admin.Users.create');
    }

    /**
     * ---------------------------------------------------------------
     * LƯU NGƯỜI DÙNG MỚI (POST /admin/users)
     * ---------------------------------------------------------------
     * - Validate dữ liệu từ form
     * - Mã hóa mật khẩu trước khi lưu
     * - Chuyển hướng về danh sách kèm thông báo thành công
     */
    public function store(Request $request)
    {
        // --- Kiểm tra dữ liệu đầu vào ---
        $request->validate([
            'name'        => 'required|string|max:255',                       // Tên: bắt buộc, tối đa 255 ký tự
            'email'       => 'required|email|max:255|unique:users,email',     // Email: bắt buộc, duy nhất
            'password'    => 'required|string|min:6|confirmed',               // Mật khẩu: tối thiểu 6, phải xác nhận
            'SoDienThoai' => 'nullable|string|max:20',                        // SĐT: không bắt buộc
            'VaiTro'      => 'required|in:admin,letan,KhachHang,user',        // Vai trò: chỉ 3 giá trị hợp lệ (hỗ trợ user cũ)
        ], [
            // --- Thông báo lỗi bằng tiếng Việt ---
            'name.required'        => 'Vui lòng nhập họ và tên',
            'email.required'       => 'Vui lòng nhập email',
            'email.email'          => 'Email không đúng định dạng',
            'email.unique'         => 'Email này đã được sử dụng',
            'password.required'    => 'Vui lòng nhập mật khẩu',
            'password.min'         => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed'   => 'Xác nhận mật khẩu không khớp',
            'VaiTro.required'      => 'Vui lòng chọn vai trò',
            'VaiTro.in'            => 'Vai trò không hợp lệ',
        ]);

        // --- Tạo tài khoản mới ---
        $vaiTro = $request->VaiTro === 'user' ? 'KhachHang' : $request->VaiTro;

        User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),  // Mã hóa mật khẩu bằng bcrypt
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => $vaiTro,
        ]);

        // Chuyển về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.users.index')
                        ->with('success', 'Thêm tài khoản thành công!');
    }

    /**
     * ---------------------------------------------------------------
     * XEM CHI TIẾT (GET /admin/users/{user})
     * ---------------------------------------------------------------
     * Hiển thị toàn bộ thông tin chi tiết của một tài khoản
     * Bao gồm cả danh sách khách hàng liên kết (nếu có)
     */
    public function show(User $user)
    {
        // Load thêm quan hệ khách hàng để hiển thị
        $user->load('khachHang');
        return view('admin.Users.show', compact('user'));
    }

    /**
     * ---------------------------------------------------------------
     * FORM CHỈNH SỬA (GET /admin/users/{user}/edit)
     * ---------------------------------------------------------------
     * Hiển thị form chỉnh sửa thông tin tài khoản với dữ liệu hiện tại
     */
    public function edit(User $user)
    {
        return view('admin.Users.edit', compact('user'));
    }

    /**
     * ---------------------------------------------------------------
     * CẬP NHẬT THÔNG TIN (PUT /admin/users/{user})
     * ---------------------------------------------------------------
     * - Validate dữ liệu (email phải duy nhất trừ user hiện tại)
     * - Nếu có nhập mật khẩu mới thì mã hóa và cập nhật
     * - Nếu không nhập mật khẩu thì giữ nguyên mật khẩu cũ
     */
    public function update(Request $request, User $user)
    {
        // --- Kiểm tra dữ liệu đầu vào ---
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)], // Bỏ qua email của chính user này
            'password'    => 'nullable|string|min:6|confirmed',   // Mật khẩu: không bắt buộc khi sửa
            'SoDienThoai' => 'nullable|string|max:20',
            'VaiTro'      => 'required|in:admin,letan,KhachHang,user',
        ], [
            'name.required'        => 'Vui lòng nhập họ và tên',
            'email.required'       => 'Vui lòng nhập email',
            'email.email'          => 'Email không đúng định dạng',
            'email.unique'         => 'Email này đã được sử dụng',
            'password.min'         => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed'   => 'Xác nhận mật khẩu không khớp',
            'VaiTro.required'      => 'Vui lòng chọn vai trò',
            'VaiTro.in'            => 'Vai trò không hợp lệ',
        ]);

        // --- Chuẩn bị dữ liệu cần cập nhật ---
        $vaiTro = $request->VaiTro === 'user' ? 'KhachHang' : $request->VaiTro;

        $data = [
            'name'        => $request->name,
            'email'       => $request->email,
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => $vaiTro,
        ];

        // Chỉ cập nhật mật khẩu nếu admin có nhập mật khẩu mới
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Thực hiện cập nhật vào database
        $user->update($data);

        // Chuyển về trang danh sách kèm thông báo thành công
        return redirect()->route('admin.users.index')
                        ->with('success', 'Cập nhật tài khoản thành công!');
    }

    /**
     * ---------------------------------------------------------------
     * XÓA TÀI KHOẢN (DELETE /admin/users/{user})
     * ---------------------------------------------------------------
     * - Không cho xóa chính tài khoản đang đăng nhập
     * - Kiểm tra nếu user có liên kết khách hàng thì không cho xóa
     * - Xóa thành công thì chuyển về danh sách
     */
    public function destroy(User $user)
    {
        // --- Không cho phép tự xóa chính mình ---
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Bạn không thể xóa tài khoản của chính mình!');
        }

        // --- Kiểm tra xem user có liên kết với khách hàng không ---
        if ($user->khachHang()->exists()) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Không thể xóa tài khoản đã liên kết với khách hàng!');
        }

        // Thực hiện xóa tài khoản
        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'Xóa tài khoản thành công!');
    }
}
