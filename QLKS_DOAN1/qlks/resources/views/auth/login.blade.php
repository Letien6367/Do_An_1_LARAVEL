{{--
    ===================================================================
    TRANG ĐĂNG NHẬP
    ===================================================================
    - Trang đăng nhập cho tất cả người dùng (khách hàng, lễ tân, admin)
    - Giao diện 2 cột: bên trái branding, bên phải form đăng nhập
    - Sử dụng Bootstrap 5 + Font Awesome
--}}
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - LUXURY HOTEL</title>
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
        .bg-hotel  { background-color: #1e3a5f !important; }
        /* Feature card */
        .feature-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
        }
        /* Input icon bên trái */
        .input-icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #888; font-size: 1rem; z-index: 5;
        }
        .input-icon-field {
            padding-left: 44px !important;
        }
        /* Nút đăng nhập gradient */
        .btn-hotel-gradient {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
            color: #fff; border: none; transition: all .3s ease;
        }
        .btn-hotel-gradient:hover {
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(30,58,95,0.35);
        }
        /* Toggle password button */
        .toggle-pass {
            position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #888; cursor: pointer; z-index: 5;
        }
        /* Responsive: ẩn cột trái trên mobile */
        @media (max-width: 991.98px) {
            .left-panel { display: none !important; }
        }
    </style>
</head>
<body>

<div class="container-fluid p-0">
    <div class="row g-0 min-vh-100">

        {{-- ===== CỘT TRÁI: BRANDING ===== --}}
        <div class="col-lg-6 left-panel d-flex flex-column justify-content-center align-items-center text-white p-5">
            {{-- Logo & tên khách sạn --}}
            <div class="text-center mb-4">
                <div class="brand-logo mx-auto mb-3">
                    <i class="fas fa-hotel"></i>
                </div>
                <h1 class="fw-bold fs-2 mb-1">LUXURY<span class="text-gold">HOTEL</span></h1>
                <p class="opacity-75">Hệ thống quản lý khách sạn chuyên nghiệp</p>
            </div>

            {{-- Danh sách tính năng nổi bật --}}
            <div class="d-flex flex-column gap-3" style="max-width:340px;width:100%;">
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-bed text-gold fs-5"></i>
                    <span>Quản lý phòng thông minh</span>
                </div>
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-calendar-check text-gold fs-5"></i>
                    <span>Đặt phòng trực tuyến 24/7</span>
                </div>
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-chart-line text-gold fs-5"></i>
                    <span>Báo cáo doanh thu chi tiết</span>
                </div>
                <div class="feature-card d-flex align-items-center gap-3 p-3">
                    <i class="fas fa-users text-gold fs-5"></i>
                    <span>Quản lý khách hàng hiệu quả</span>
                </div>
            </div>
        </div>

        {{-- ===== CỘT PHẢI: FORM ĐĂNG NHẬP ===== --}}
        <div class="col-lg-6 d-flex align-items-center justify-content-center bg-white p-4 p-md-5">
            <div style="max-width:440px;width:100%;">

                {{-- Tiêu đề --}}
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color:#1e3a5f;">Chào mừng trở lại!</h2>
                    <p class="text-muted">Đăng nhập để tiếp tục sử dụng hệ thống</p>
                </div>

                {{-- Thông báo lỗi từ server --}}
                @if($errors->any())
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- Thông báo thành công (ví dụ: đăng ký xong, đăng xuất xong) --}}
                @if(session('success'))
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Form đăng nhập --}}
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    {{-- Trường Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">Email</label>
                        <div class="position-relative">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="email" name="email" value="{{ old('email') }}"
                                   class="form-control form-control-lg input-icon-field rounded-3 {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                   placeholder="Nhập địa chỉ email" required>
                        </div>
                    </div>

                    {{-- Trường Mật khẩu --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold" style="color:#1e3a5f;">Mật khẩu</label>
                        <div class="position-relative">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" id="password"
                                   class="form-control form-control-lg input-icon-field rounded-3"
                                   placeholder="Nhập mật khẩu" required>
                            {{-- Nút hiện/ẩn mật khẩu --}}
                            <button type="button" class="toggle-pass" onclick="togglePassword()">
                                <i class="fas fa-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Ghi nhớ đăng nhập --}}
                    <div class="mb-4">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted" for="remember">Ghi nhớ đăng nhập</label>
                        </div>
                    </div>

                    {{-- Nút đăng nhập --}}
                    <button type="submit" class="btn btn-hotel-gradient btn-lg w-100 rounded-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-sign-in-alt"></i> Đăng nhập
                    </button>
                </form>

                {{-- Đường kẻ phân cách --}}
                <div class="d-flex align-items-center my-4">
                    <hr class="flex-grow-1"><span class="px-3 text-muted small">hoặc</span><hr class="flex-grow-1">
                </div>

                {{-- Link đăng ký --}}
                <p class="text-center text-muted mb-0">
                    Chưa có tài khoản?
                    <a href="{{ route('register') }}" class="fw-semibold text-decoration-none" style="color:#1e3a5f;">Đăng ký ngay</a>
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
     * - Chuyển type input giữa 'password' và 'text'
     * - Đổi icon mắt tương ứng
     */
    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
</body>
</html>
