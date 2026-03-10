{{-- 
    ===================================================================
    LAYOUT CHÍNH CHO GIAO DIỆN QUẢN LÝ (ADMIN)
    ===================================================================
    - Sử dụng Bootstrap 5 
    - Bao gồm: Sidebar (menu bên trái), Header (thanh trên), Nội dung chính
    - Các trang admin con sẽ kế thừa layout này bằng @extends('layout.quanly')
--}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tiêu đề trang admin --}}
    <title>@yield('title', 'Admin') - {{ config('app.name', 'QLKS') }}</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <style>
        /* CSS bổ sung cho layout admin */
        body { font-family: 'Instrument Sans', sans-serif; background-color: #f4f6f9; }

        /* Sidebar cố định bên trái */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: 260px; height: 100vh;
            background: linear-gradient(180deg, #1e3a5f 0%, #2d5a87 100%);
            z-index: 1040; overflow-y: auto;
            transition: transform 0.3s ease;
        }
        /* Vùng nội dung chính (bên phải sidebar) */
        .main-wrapper { margin-left: 260px; min-height: 100vh; }

        /* Link menu trong sidebar */
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8); border-radius: 8px;
            padding: 10px 15px; margin-bottom: 2px; transition: all 0.2s;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .sidebar .nav-link.active { background: rgba(240,193,75,0.2); color: #f0c14b; }
        .sidebar .nav-link i { width: 22px; text-align: center; }

        /* Label phân nhóm menu */
        .menu-label {
            color: rgba(255,255,255,0.4); font-size: 0.72rem;
            font-weight: 600; text-transform: uppercase;
            letter-spacing: 1px; padding: 0 15px; margin-top: 15px; margin-bottom: 5px;
        }

        /* Responsive: ẩn sidebar trên mobile */
        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
        }

        /* Ẩn sidebar và header khi in, nội dung chiếm toàn trang */
        @media print {
            .sidebar { display: none !important; }
            .main-wrapper { margin-left: 0 !important; }
            .main-wrapper > .border-bottom { display: none !important; }
        }
    </style>

    {{-- Cho phép các trang con thêm CSS riêng --}}
    @stack('styles')
</head>
<body>

    {{-- ============================= --}}
    {{-- SIDEBAR - MENU BÊN TRÁI      --}}
    {{-- ============================= --}}
    <aside class="sidebar" id="sidebar">
        {{-- Logo trong sidebar --}}
        <div class="p-3 border-bottom border-secondary d-flex align-items-center gap-2">
            <div class="d-flex align-items-center justify-content-center rounded-3"
                 style="width:40px;height:40px;background:linear-gradient(135deg,#f0c14b,#e8a317);">
                <i class="fas fa-hotel text-dark"></i>
            </div>
            <span class="text-white fw-bold">LUXURY<span style="color:#f0c14b;">HOTEL</span></span>
        </div>

        {{-- Danh sách menu --}}
        <nav class="p-3">
            {{-- Nhóm: Menu chính --}}
            <p class="menu-label">Menu chính</p>
            <a href="{{ url('/admin') }}" class="nav-link {{ request()->is('admin') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>

            <hr class="border-secondary my-2">

            {{-- Nhóm: Quản lý --}}
            <p class="menu-label">Quản lý</p>

            {{-- Quản lý phòng --}}
            <a href="{{ route('admin.phong.index') }}" class="nav-link {{ request()->routeIs('admin.phong.*') ? 'active' : '' }}">
                <i class="fas fa-bed me-2"></i> Quản lý phòng
            </a>
            {{-- Loại phòng --}}
            <a href="{{ url('/admin/loai-phong') }}" class="nav-link {{ request()->is('admin/loai-phong*') ? 'active' : '' }}">
                <i class="fas fa-layer-group me-2"></i> Loại phòng
            </a>
            {{-- Trạng thái phòng --}}
            <a href="{{ url('/admin/trang-thai-phong') }}" class="nav-link {{ request()->is('admin/trang-thai-phong*') ? 'active' : '' }}">
                <i class="fas fa-toggle-on me-2"></i> Trạng thái phòng
            </a>
            {{-- Trạng thái đặt phòng --}}
            <a href="{{ route('admin.trang-thai-dat-phong.index') }}" class="nav-link {{ request()->routeIs('admin.trang-thai-dat-phong.*') ? 'active' : '' }}">
                <i class="fas fa-clipboard-list me-2"></i> TT đặt phòng
            </a>
            {{-- Đặt phòng --}}
            <a href="{{ route('admin.dat-phong.index') }}" class="nav-link {{ request()->routeIs('admin.dat-phong.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check me-2"></i> Đặt phòng
            </a>
            {{-- Khách hàng --}}
            <a href="{{ url('/admin/khach-hang') }}" class="nav-link {{ request()->is('admin/khach-hang*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Khách hàng
            </a>
            {{-- Hóa đơn --}}
            <a href="{{ url('/admin/hoa-don') }}" class="nav-link {{ request()->is('admin/hoa-don*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice me-2"></i> Hóa đơn
            </a>
            {{-- Doanh thu --}}
            <a href="{{ url('/admin/doanh-thu') }}" class="nav-link {{ request()->is('admin/doanh-thu*') ? 'active' : '' }}">
                <i class="fas fa-chart-line me-2"></i> Doanh thu
            </a>

            <hr class="border-secondary my-2">

            {{-- Nhóm: Hệ thống --}}
            <p class="menu-label">Hệ thống</p>

            {{-- 
                Quản lý tài khoản: CHỈ HIỂN THỊ CHO ADMIN
                Nếu không phải admin thì không thấy menu này
            --}}
            @if(Auth::user()->VaiTro === 'admin')
            <a href="{{ url('/admin/users') }}" class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
                <i class="fas fa-user-cog me-2"></i> Quản lý tài khoản
            </a>
            @endif
            {{-- Về trang chủ --}}
            <a href="{{ url('/') }}" class="nav-link">
                <i class="fas fa-home me-2"></i> Về trang chủ
            </a>

            <hr class="border-secondary my-2">

            {{-- Nút đăng xuất --}}
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent w-100 text-start">
                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                </button>
            </form>
        </nav>
    </aside>

    {{-- ============================= --}}
    {{-- VÙNG NỘI DUNG CHÍNH          --}}
    {{-- ============================= --}}
    <div class="main-wrapper">

        {{-- Header phía trên --}}
        <header class="bg-white shadow-sm p-3 d-flex justify-content-between align-items-center sticky-top d-print-none">
            <div class="d-flex align-items-center gap-3">
                {{-- Nút mở sidebar trên mobile --}}
                <button class="btn btn-outline-secondary d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            {{-- Thông tin user admin --}}
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    {{-- Avatar hiển thị chữ cái đầu của tên --}}
                    <div class="d-flex align-items-center justify-content-center rounded-circle fw-bold"
                         style="width:35px;height:35px;background:linear-gradient(135deg,#f0c14b,#e8a317);color:#1e3a5f;">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <div class="text-start d-none d-md-block">
                        <div class="fw-semibold small">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="text-muted" style="font-size:0.75rem;">
                            {{ Auth::user()->VaiTro === 'admin' ? 'Quản trị viên' : 'Người dùng' }}
                        </div>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        {{-- Vùng nội dung chính - được thay thế bởi @section('content') của trang con --}}
        <main class="p-4">
            @yield('content')
        </main>
    </div>

    {{-- Bootstrap 5 JS Bundle --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // === Đóng sidebar khi click ra ngoài trên mobile ===
        document.addEventListener('click', function(e) {
            var sidebar = document.getElementById('sidebar');
            var toggleBtn = document.querySelector('.d-lg-none');
            // Nếu đang ở màn hình nhỏ và click ra ngoài sidebar
            if (window.innerWidth <= 992 && !sidebar.contains(e.target) && toggleBtn && !toggleBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        });
    </script>

    {{-- Cho phép các trang con thêm JS riêng --}}
    @stack('scripts')
</body>
</html>
