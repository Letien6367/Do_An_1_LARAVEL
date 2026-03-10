{{--
    ===================================================================
    TRANG ĐĂNG KÝ TÀI KHOẢN
    ===================================================================
    - Trang đăng ký cho khách hàng mới
    - Giao diện 2 cột: bên trái branding, bên phải form đăng ký
    - Bao gồm: Họ tên, Email, SĐT, Mật khẩu, Xác nhận MK, Điều khoản
    - Có thanh đo độ mạnh mật khẩu (password strength meter)
--}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - LUXURY HOTEL</title>
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        body {
            font-family: 'Instrument Sans', sans-serif;
            min-height: 100vh;
        }
        /* Cột trái: nền gradient xanh đậm */
        .left-panel {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 50%, #1e3a5f 100%);
            min-height: 100vh;
        }
        /* Logo icon tròn vàng */
        .brand-logo {
            width: 90px; height: 90px;
            background: linear-gradient(135deg, #f0c14b 0%, #e8a317 100%);
            border-radius: 22px;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; color: #1e3a5f;
            box-shadow: 0 16px 32px rgba(240,193,75,0.3);
        }
        .text-gold { color: #d4af37 !important; }
        /* Feature card trong cột trái */
        .feature-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
        }
        /* Icon bên trái input */
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #888; font-size: 1rem; z-index: 5;
        }
        .input-icon-field {
            padding-left: 44px !important;
        }
        /* Nút đăng ký gradient xanh lá */
        .btn-register-gradient {
            background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
            color: #fff; border: none; transition: all .3s ease;
        }
        .btn-register-gradient:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(39,174,96,0.35);
        }
        /* Toggle password */
        .toggle-pass {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #888; cursor: pointer; z-index: 5;
        }
        /* Thanh đo độ mạnh mật khẩu */
        .strength-bar {
            flex: 1; height: 4px; background: #e0e0e0;
            border-radius: 2px; transition: all .3s ease;
        }
        .strength-bar.weak   { background: #e74c3c; }
        .strength-bar.medium { background: #f39c12; }
        .strength-bar.strong { background: #27ae60; }
        /* Ẩn cột trái trên mobile */
        @media (max-width: 991.98px) {
            .left-panel { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        {{-- ===== CỘT TRÁI: BRANDING ===== --}}
        <div class="col-lg-5 left-panel d-flex flex-column justify-content-center align-items-center text-white p-5">
            {{-- Logo & tên thương hiệu --}}
            <div class="text-center mb-4">
                <div class="brand-logo mx-auto mb-3">
                    <i class="fas fa-hotel"></i>
                </div>
                <h1 class="fw-bold fs-2 mb-1">LUXURY<span class="text-gold">HOTEL</span></h1>
                <p class="opacity-75">Hệ thống quản lý khách sạn chuyên nghiệp</p>
            </div>

            {{-- Lợi ích khi đăng ký --}}
            <div class="d-flex flex-column gap-3" style="max-width:340px;width:100%;">
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-gift text-gold fs-5"></i>
                    <span>Ưu đãi độc quyền cho thành viên</span>
                </div>
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-clock text-gold fs-5"></i>
                    <span>Đặt phòng nhanh chóng, tiện lợi</span>
                </div>
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-history text-gold fs-5"></i>
                    <span>Theo dõi lịch sử đặt phòng</span>
                </div>
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-star text-gold fs-5"></i>
                    <span>Tích điểm thưởng mỗi lần đặt</span>
                </div>
            </div>
        </div>

        {{-- ===== CỘT PHẢI: FORM ĐĂNG KÝ ===== --}}
        <div class="col-lg-7 d-flex align-items-center justify-content-center bg-white p-4 p-md-5" style="overflow-y:auto;">
            <div style="max-width:480px;width:100%;">

                {{-- Tiêu đề form --}}
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color:#1e3a5f;">Tạo tài khoản mới</h2>
                    <p class="text-muted">Đăng ký để trải nghiệm dịch vụ tốt nhất</p>
                </div>

                {{-- Hiển thị danh sách lỗi validation --}}
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form đăng ký tài khoản --}}
                <form action="{{ route('register') }}" method="POST">
                    @csrf

                    {{-- Trường: Họ và tên --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">
                            Họ và tên <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fas fa-user input-icon"></i>
                            <input type="text" name="name" value="{{ old('name') }}"
                                   class="form-control form-control-lg input-icon-field rounded-3 {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                   placeholder="Nhập họ và tên" required>
                        </div>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">
                            Email <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control form-control-lg input-icon-field rounded-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="Nhập địa chỉ email" required>
                        </div>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Số điện thoại (không bắt buộc) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">Số điện thoại</label>
                        <div class="position-relative">
                            <i class="fas fa-phone input-icon"></i>
                            <input type="tel" name="SoDienThoai" value="{{ old('SoDienThoai') }}"
                                   class="form-control form-control-lg input-icon-field rounded-3"
                                   placeholder="Nhập số điện thoại">
                        </div>
                    </div>

                    {{-- Trường: Mật khẩu + thanh đo độ mạnh --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">
                            Mật khẩu <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg input-icon-field rounded-3 {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                   placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" required>
                            <button type="button" class="toggle-pass" onclick="togglePassword('password','toggleIcon1')">
                                <i class="fas fa-eye" id="toggleIcon1"></i>
                            </button>
                        </div>
                        {{-- Thanh đo độ mạnh mật khẩu (4 thanh) --}}
                        <div class="d-flex gap-1 mt-2" id="strengthBars">
                            <div class="strength-bar" id="bar1"></div>
                            <div class="strength-bar" id="bar2"></div>
                            <div class="strength-bar" id="bar3"></div>
                            <div class="strength-bar" id="bar4"></div>
                        </div>
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Xác nhận mật khẩu --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">
                            Xác nhận mật khẩu <span class="text-danger">*</span>
                        </label>
                        <div class="position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="form-control form-control-lg input-icon-field rounded-3"
                                   placeholder="Nhập lại mật khẩu" required>
                            <button type="button" class="toggle-pass" onclick="togglePassword('password_confirmation','toggleIcon2')">
                                <i class="fas fa-eye" id="toggleIcon2"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Checkbox đồng ý điều khoản --}}
                    <div class="form-check mb-4">
                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                        <label class="form-check-label text-muted" for="terms">
                            Tôi đồng ý với điều khoản dịch vụ và chính sách bảo mật của LUXURY HOTEL
                        </label>
                    </div>

                    {{-- Nút đăng ký --}}
                    <button type="submit" class="btn btn-register-gradient btn-lg w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-user-plus"></i> Đăng ký
                    </button>
                </form>

                {{-- Đường kẻ phân cách --}}
                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1"><span class="px-3 text-muted small">hoặc</span><hr class="flex-grow-1">
                </div>

                {{-- Link quay về đăng nhập --}}
                <p class="text-center text-muted mb-0">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="fw-semibold text-decoration-none" style="color:#1e3a5f;">Đăng nhập ngay</a>
                </p>
            </div>
        </div>

    </div>
</div>

{{-- Bootstrap 5 JS --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    /**
     * Hàm bật/tắt hiển thị mật khẩu
     * @param {string} inputId  - ID của ô input mật khẩu
     * @param {string} iconId   - ID của icon mắt
     */
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon  = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    /**
     * Kiểm tra độ mạnh mật khẩu khi người dùng gõ
     * - Dài >= 6 ký tự: +1 điểm
     * - Dài >= 8 ký tự: +1 điểm
     * - Có chữ hoa + chữ thường: +1 điểm
     * - Có số: +1 điểm
     * - Có ký tự đặc biệt: +1 điểm
     * Tối đa 4 thanh sáng: yếu (đỏ) / trung bình (vàng) / mạnh (xanh)
     */
    document.getElementById('password').addEventListener('input', function() {
        const password = this.value;
        const bars = document.querySelectorAll('.strength-bar');

        // Reset tất cả thanh về mặc định (xám)
        bars.forEach(bar => bar.className = 'strength-bar');

        let strength = 0;
        if (password.length >= 6) strength++;
        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password) && /[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[^A-Za-z0-9]/.test(password)) strength++;

        // Tô màu các thanh theo mức độ mạnh
        for (let i = 0; i < Math.min(strength, 4); i++) {
            if (strength <= 1) {
                bars[i].classList.add('weak');     // Yếu – đỏ
            } else if (strength <= 3) {
                bars[i].classList.add('medium');    // Trung bình – vàng
            } else {
                bars[i].classList.add('strong');    // Mạnh – xanh
            }
        }
    });
</script>
</body>
</html>
