{{-- 
    ===================================================================
    LAYOUT CHÍNH CHO GIAO DIỆN NGƯỜI DÙNG (KHÁCH HÀNG)
    ===================================================================
    - Sử dụng Bootstrap 5 để xây dựng giao diện
    - Bao gồm: Navbar (thanh điều hướng), Nội dung chính, Footer (chân trang)
    - Các trang con sẽ kế thừa layout này bằng @extends('layout.nguoidung')
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Token CSRF để bảo vệ form khỏi tấn công CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tiêu đề trang: lấy từ @section('title') của trang con, mặc định là 'Trang chủ' --}}
    <title>@yield('title', 'Trang chủ') - {{ config('app.name', 'Luxury Hotel') }}</title>

    {{-- Bootstrap 5 CSS - Framework CSS chính --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome - Thư viện icon (biểu tượng) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- Google Fonts - Font chữ đẹp --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700&family=playfair-display:400,500,600,700" rel="stylesheet" />

    <style>
        /* === CSS tùy chỉnh bổ sung cho Bootstrap === */
        body { font-family: 'Instrument Sans', sans-serif; }
        h1,h2,h3,h4,h5,h6 { font-family: 'Playfair Display', serif; }

        /* Màu chủ đạo của khách sạn */
        .bg-hotel { background-color: #1e3a5f !important; }
        .text-hotel { color: #1e3a5f !important; }
        .text-gold { color: #d4af37 !important; }

        /* Nút vàng sang trọng */
        .btn-gold {
            background: linear-gradient(135deg, #d4af37 0%, #c9a227 100%);
            color: #1e3a5f; font-weight: 700; border: none;
        }
        .btn-gold:hover { background: linear-gradient(135deg, #c9a227 0%, #b8931f 100%); color: #1e3a5f; }

        /* Nút màu chủ đạo khách sạn */
        .btn-hotel { background-color: #1e3a5f; color: #fff; border: none; }
        .btn-hotel:hover { background-color: #152a45; color: #fff; }

        /* Link footer */
        .footer-link { color: rgba(255,255,255,0.7); text-decoration: none; }
        .footer-link:hover { color: #d4af37; }

        /* Nút cuộn lên đầu trang */
        .back-to-top {
            position: fixed; bottom: 30px; right: 30px;
            width: 45px; height: 45px;
            background: #d4af37; color: #1e3a5f;
            border: none; border-radius: 50%;
            font-size: 1.2rem; display: none;
            align-items: center; justify-content: center;
            z-index: 999; cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .back-to-top.show { display: flex; }
    </style>

    {{-- Cho phép các trang con thêm CSS riêng bằng @push('styles') --}}
    @stack('styles')
</head>
<body>

    {{-- ============================= --}}
    {{-- NAVBAR - THANH ĐIỀU HƯỚNG     --}}
    {{-- ============================= --}}
    {{-- Sử dụng Bootstrap Navbar, cố định ở đầu trang (fixed-top) --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-hotel fixed-top shadow" id="mainNavbar">
        <div class="container">

            {{-- Logo khách sạn - liên kết về trang chủ --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                <div class="d-flex align-items-center justify-content-center rounded-3"
                     style="width:42px;height:42px;background:linear-gradient(135deg,#d4af37,#c9a227);">
                    <i class="fas fa-hotel text-dark fs-5"></i>
                </div>
                <span class="fw-bold fs-5">LUXURY<span class="text-gold">HOTEL</span></span>
            </a>

            {{-- Nút hamburger - chỉ hiện trên màn hình nhỏ (mobile) --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            {{-- Danh sách menu --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center gap-2">

                    {{-- Kiểm tra nếu người dùng ĐÃ đăng nhập --}}
                    @auth
                        {{-- Dropdown hiển thị tên user và các tùy chọn --}}
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                               role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle fs-5"></i>
                                {{ Auth::user()->HoTen ?? Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                {{-- Link đến trang lịch sử đặt phòng --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('dat-phong.lich-su') }}">
                                        <i class="fas fa-history me-2 text-gold"></i> Lịch sử đặt phòng
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                {{-- Form đăng xuất (dùng POST để bảo mật) --}}
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        {{-- Nếu CHƯA đăng nhập: hiện nút Đăng nhập --}}
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-gold btn-sm px-3">
                                <i class="fas fa-sign-in-alt me-1"></i> Đăng nhập
                            </a>
                        </li>
                    @endauth

                </ul>
            </div>
        </div>
    </nav>

    {{-- ============================= --}}
    {{-- NỘI DUNG CHÍNH CỦA TRANG     --}}
    {{-- ============================= --}}
    {{-- @yield('content') sẽ được thay thế bởi nội dung của trang con --}}
    <main style="min-height:60vh;">
        @yield('content')
    </main>

    {{-- ============================= --}}
    {{-- FOOTER - CHÂN TRANG           --}}
    {{-- ============================= --}}
    <footer class="bg-hotel text-white pt-5 pb-3">
        <div class="container">
            <div class="row g-4">

                {{-- Cột 1: Thông tin thương hiệu --}}
                <div class="col-lg-6 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="d-flex align-items-center justify-content-center rounded-3"
                             style="width:42px;height:42px;background:linear-gradient(135deg,#d4af37,#c9a227);">
                            <i class="fas fa-hotel text-dark fs-5"></i>
                        </div>
                        <span class="fw-bold fs-5">LUXURY<span class="text-gold">HOTEL</span></span>
                    </div>
                    <p class="text-white-50 small">
                        Chúng tôi mang đến trải nghiệm nghỉ dưỡng đẳng cấp 5 sao với dịch vụ hoàn hảo và không gian sang trọng.
                    </p>
                </div>



                {{-- Cột 2: Thông tin liên hệ --}}
                <div class="col-lg-6 col-md-6">
                    <h6 class="fw-bold mb-3">Liên hệ</h6>
                    <div class="d-flex gap-3 mb-2">
                        <div class="text-gold"><i class="fas fa-map-marker-alt"></i></div>
                        <p class="text-white-50 small mb-0">Nguyễn Văn cừ, Quận Ninh Kiều, TP. Cần Thơ</p>
                    </div>
                    <div class="d-flex gap-3 mb-2">
                        <div class="text-gold"><i class="fas fa-phone-alt"></i></div>
                        <p class="text-white-50 small mb-0">+84 0328343771</p>
                    </div>
                    <div class="d-flex gap-3">
                        <div class="text-gold"><i class="fas fa-envelope"></i></div>
                        <p class="text-white-50 small mb-0">Letienminh304@gmail.com</p>
                    </div>
                </div>
            </div>

            <hr class="border-secondary my-4">

            {{-- Bản quyền --}}
            <div class="text-center">
                <p class="text-white-50 mb-0 small">&copy; {{ date('Y') }} Luxury Hotel. Tất cả quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    {{-- Nút cuộn lên đầu trang --}}
    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="fas fa-arrow-up"></i>
    </button>

    {{-- Bootstrap 5 JS Bundle (bao gồm Popper.js cho dropdown, tooltip...) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // === Xử lý hiệu ứng khi cuộn trang ===
        window.addEventListener('scroll', function() {
            var backToTop = document.getElementById('backToTop');
            // Khi cuộn xuống hơn 100px thì hiện nút back-to-top
            if (window.scrollY > 100) {
                backToTop.classList.add('show');
            } else {
                backToTop.classList.remove('show');
            }
        });
    </script>

    {{-- Cho phép các trang con thêm JS riêng bằng @push('scripts') --}}
    @stack('scripts')
</body>
</html>
