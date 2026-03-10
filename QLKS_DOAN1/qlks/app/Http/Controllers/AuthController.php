<?php

/**
 * ===================================================================
 * AuthController - Controller xác thực người dùng
 * ===================================================================
 * Controller xử lý toàn bộ chức năng xác thực (Authentication):
 * - Hiển thị form đăng nhập (showLoginForm)
 * - Xử lý đăng nhập (login)
 * - Hiển thị form đăng ký (showRegisterForm)
 * - Xử lý đăng ký tài khoản mới (register)
 * - Đăng xuất (logout)
 *
 * Phân quyền theo vai trò (VaiTro):
 * - 'Admin': Quản trị viên → chuyển đến dashboard quản lý
 * - 'LeTan': Lễ tân → chuyển đến dashboard quản lý
 * - 'KhachHang': Khách hàng → chuyển đến trang chủ
 */

namespace App\Http\Controllers;

// Import các class cần thiết
use App\Models\User;                   // Model người dùng
use Illuminate\Http\Request;            // Class xử lý HTTP Request
use Illuminate\Support\Facades\Auth;    // Facade xác thực (login, logout, check)
use Illuminate\Support\Facades\Hash;    // Facade mã hóa mật khẩu (bcrypt)

class AuthController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * Phương thức: showLoginForm (GET)
     * ---------------------------------------------------------------
     * Mục đích: Hiển thị trang đăng nhập
     * Lưu ý: Nếu đã đăng nhập → chuyển thẳng đến dashboard
     * Route: GET /login
     */
    public function showLoginForm()
    {
        // Kiểm tra nếu user đã đăng nhập rồi → không cần hiện form login nữa
        // Chuyển thẳng đến trang quản lý (dashboard)
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        // Hiển thị trang đăng nhập
        return view('auth.login');
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: login (POST)
     * ---------------------------------------------------------------
     * Mục đích: Xử lý đăng nhập khi user submit form
     * Quy trình:
     *   1. Validate email + mật khẩu
     *   2. Thử xác thực với database
     *   3. Nếu thành công → phân quyền chuyển hướng theo vai trò
     *   4. Nếu thất bại → quay lại form với thông báo lỗi
     * Route: POST /login
     */
    public function login(Request $request)
    {
        // Bước 1: Validate dữ liệu từ form đăng nhập
        // - email: bắt buộc, phải đúng định dạng email
        // - password: bắt buộc, tối thiểu 6 ký tự
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            // Thông báo lỗi tùy chỉnh bằng tiếng Việt
            'email.required'    => 'Vui lòng nhập email',
            'email.email'       => 'Email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
            'password.min'      => 'Mật khẩu phải có ít nhất 6 ký tự',
        ]);

        // Bước 2: Lấy thông tin đăng nhập từ request
        // only(): chỉ lấy 2 trường email và password (bảo mật)
        $credentials = $request->only('email', 'password');

        // Kiểm tra checkbox "Ghi nhớ đăng nhập"
        // Nếu checked → session sẽ được giữ lâu hơn
        $remember = $request->has('remember');

        // Bước 3: Thử xác thực với database
        // Auth::attempt() sẽ:
        //   - Tìm user theo email
        //   - So sánh mật khẩu (đã hash) với password nhập vào
        //   - Nếu đúng → tạo session đăng nhập, trả về true
        //   - Nếu sai → trả về false
        if (Auth::attempt($credentials, $remember)) {
            // Tạo lại session ID để chống tấn công session fixation
            $request->session()->regenerate();

            // Lấy thông tin user vừa đăng nhập
            $user = Auth::user();

            // Bước 4: Phân quyền chuyển hướng dựa theo vai trò (VaiTro)
            // Chuyển về chữ thường để so sánh không phân biệt hoa/thường
            $vaiTro = strtolower($user->VaiTro);
            
            // Admin hoặc Lễ tân → chuyển đến trang quản lý (dashboard)
            if ($vaiTro === 'admin' || $vaiTro === 'letan') {
                return redirect()->intended(route('admin.dashboard'));
            }
            
            // Khách hàng → chuyển đến trang chủ
            return redirect()->intended('/');
        }

        // Bước 5: Đăng nhập thất bại → quay lại form với thông báo lỗi
        // withErrors(): gắn lỗi vào trường email
        // withInput(): giữ lại giá trị email đã nhập (không giữ password)
        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ])->withInput($request->only('email', 'remember'));
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: showRegisterForm (GET)
     * ---------------------------------------------------------------
     * Mục đích: Hiển thị trang đăng ký tài khoản
     * Lưu ý: Nếu đã đăng nhập → chuyển đến dashboard
     * Route: GET /register
     */
    public function showRegisterForm()
    {
        // Nếu đã đăng nhập → không cần đăng ký nữa
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        // Hiển thị trang đăng ký
        return view('auth.register');
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: register (POST)
     * ---------------------------------------------------------------
     * Mục đích: Xử lý tạo tài khoản mới khi user submit form đăng ký
     * Quy trình:
     *   1. Validate dữ liệu form
     *   2. Tạo user mới trong database (mật khẩu được hash)
     *   3. Tự động đăng nhập user vừa tạo
     *   4. Chuyển hướng về trang chủ
     * Route: POST /register
     */
    public function register(Request $request)
    {
        // Bước 1: Validate dữ liệu đăng ký
        // - name: bắt buộc, tối đa 255 ký tự
        // - email: bắt buộc, đúng định dạng, phải duy nhất trong bảng users
        // - password: bắt buộc, tối thiểu 6 ký tự, confirmed (phải khớp với password_confirmation)
        // - SoDienThoai: không bắt buộc
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|min:6|confirmed',
            'SoDienThoai' => 'nullable|string|max:20',
        ], [
            // Thông báo lỗi bằng tiếng Việt
            'name.required'      => 'Vui lòng nhập họ tên',
            'email.required'     => 'Vui lòng nhập email',
            'email.email'        => 'Email không hợp lệ',
            'email.unique'       => 'Email đã được sử dụng',
            'password.required'  => 'Vui lòng nhập mật khẩu',
            'password.min'       => 'Mật khẩu phải có ít nhất 6 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        // Bước 2: Tạo tài khoản mới trong bảng users
        // - Hash::make(): mã hóa mật khẩu bằng bcrypt trước khi lưu
        //   (không bao giờ lưu mật khẩu dạng plain text!)
        // - VaiTro luôn = 'KhachHang' (chỉ admin mới tạo được tài khoản admin/letan)
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),  // Mã hóa mật khẩu
            'SoDienThoai' => $request->SoDienThoai,
            'VaiTro'      => 'KhachHang', // Vai trò mặc định: Khách hàng
        ]);

        // Bước 3: Tự động đăng nhập cho user vừa đăng ký
        // Không cần user phải đăng nhập lại sau khi đăng ký
        Auth::login($user);

        // Bước 4: Chuyển hướng về trang chủ kèm thông báo thành công
        return redirect('/')->with('success', 'Đăng ký thành công!');
    }

    /**
     * ---------------------------------------------------------------
     * Phương thức: logout (POST)
     * ---------------------------------------------------------------
     * Mục đích: Đăng xuất người dùng khỏi hệ thống
     * Quy trình:
     *   1. Xóa phiên đăng nhập (Auth::logout)
     *   2. Hủy toàn bộ session hiện tại (bảo mật)
     *   3. Tạo CSRF token mới (chống tấn công CSRF)
     *   4. Chuyển hướng về trang đăng nhập
     * Route: POST /logout
     */
    public function logout(Request $request)
    {
        // Xóa thông tin xác thực của user khỏi session
        Auth::logout();

        // Hủy toàn bộ dữ liệu session (bảo mật: xóa sạch mọi thông tin phiên cũ)
        $request->session()->invalidate();

        // Tạo CSRF token mới để chống tấn công CSRF sau khi logout
        $request->session()->regenerateToken();

        // Chuyển hướng về trang đăng nhập kèm thông báo
        return redirect('/login')->with('success', 'Đăng xuất thành công!');
    }
}
