{{--
    ===================================================================
    TRANG XEM CHI TIẾT TÀI KHOẢN
    ===================================================================
    Giao diện Bootstrap 5 thuần
    CHỈ ADMIN MỚI ĐƯỢC VÀO (kiểm tra ở Controller)
    
    Bố cục 2 cột:
    - Cột trái (4 cột): Thẻ profile - Avatar, tên, vai trò, thông tin liên hệ
    - Cột phải (8 cột): Thông tin chi tiết + Danh sách khách hàng liên kết
--}}

@extends('layout.quanly')

@section('title', 'Chi tiết tài khoản')

@section('content')

{{-- ==================== TIÊU ĐỀ + CÁC NÚT THAO TÁC ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">
            <i class="fas fa-user me-2"></i>Chi tiết tài khoản
        </h2>
        <p class="text-muted mb-0">Xem thông tin: <strong>{{ $user->name }}</strong></p>
    </div>
    {{-- Nhóm nút: Quay lại + Chỉnh sửa --}}
    <div class="d-flex gap-2">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
            <i class="fas fa-edit me-1"></i> Chỉnh sửa
        </a>
    </div>
</div>

{{-- ==================== BỐ CỤC 2 CỘT ==================== --}}
<div class="row g-4">

    {{-- ========== CỘT TRÁI: THẺ PROFILE (4/12 cột) ========== --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">

            {{-- 
                Phần header: nền gradient xanh lam
                Hiển thị avatar tròn (chữ cái đầu) + tên + vai trò
            --}}
            <div class="card-body text-center text-white py-5" 
                 style="background: linear-gradient(135deg, #1e3a5f, #2d5a87); border-radius: 0.375rem 0.375rem 0 0;">
                
                {{-- 
                    Avatar tròn lớn
                    Màu nền khác nhau theo vai trò:
                    - admin = đỏ (bg-danger)
                    - letan = vàng (bg-warning)
                    - user  = xanh lá (bg-success) 
                --}}
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 fw-bold
                    {{ $user->VaiTro === 'admin' ? 'bg-danger' : ($user->VaiTro === 'letan' ? 'bg-warning text-dark' : 'bg-success') }}"
                    style="width:90px;height:90px;font-size:2.5rem;border:4px solid rgba(255,255,255,0.3);">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>

                {{-- Tên người dùng --}}
                <h4 class="fw-bold mb-1">{{ $user->name }}</h4>

                {{-- Vai trò hiển thị dưới tên --}}
                <span class="badge {{ $user->VaiTro === 'admin' ? 'bg-danger' : ($user->VaiTro === 'letan' ? 'bg-warning text-dark' : 'bg-success') }} fs-6">
                    @if($user->VaiTro === 'admin')
                        <i class="fas fa-shield-alt me-1"></i>Quản trị viên
                    @elseif($user->VaiTro === 'letan')
                        <i class="fas fa-concierge-bell me-1"></i>Lễ tân
                    @else
                        <i class="fas fa-user me-1"></i>Khách hàng
                    @endif
                </span>
            </div>

            {{-- Phần body: các dòng thông tin liên hệ --}}
            <div class="card-body">
                <ul class="list-group list-group-flush">

                    {{-- Dòng: Email --}}
                    <li class="list-group-item d-flex align-items-center px-0">
                        <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-3 text-center" style="width:40px;">
                            <i class="fas fa-envelope text-primary"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Email</small>
                            <span class="fw-semibold">{{ $user->email }}</span>
                        </div>
                    </li>

                    {{-- Dòng: Số điện thoại --}}
                    <li class="list-group-item d-flex align-items-center px-0">
                        <div class="rounded-3 bg-success bg-opacity-10 p-2 me-3 text-center" style="width:40px;">
                            <i class="fas fa-phone text-success"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Số điện thoại</small>
                            <span class="fw-semibold">{{ $user->SoDienThoai ?: 'Chưa cập nhật' }}</span>
                        </div>
                    </li>

                    {{-- Dòng: Ngày tạo tài khoản --}}
                    <li class="list-group-item d-flex align-items-center px-0">
                        <div class="rounded-3 bg-info bg-opacity-10 p-2 me-3 text-center" style="width:40px;">
                            <i class="fas fa-calendar-alt text-info"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Ngày tạo</small>
                            <span class="fw-semibold">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </li>

                    {{-- Dòng: Ngày cập nhật gần nhất --}}
                    <li class="list-group-item d-flex align-items-center px-0">
                        <div class="rounded-3 bg-warning bg-opacity-10 p-2 me-3 text-center" style="width:40px;">
                            <i class="fas fa-clock text-warning"></i>
                        </div>
                        <div>
                            <small class="text-muted d-block">Cập nhật lần cuối</small>
                            <span class="fw-semibold">{{ $user->updated_at ? $user->updated_at->format('d/m/Y H:i') : 'N/A' }}</span>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ========== CỘT PHẢI: THÔNG TIN CHI TIẾT (8/12 cột) ========== --}}
    <div class="col-lg-8">

        {{-- ---- Card 1: Thông tin tài khoản ---- --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-id-card me-2 text-warning"></i>Thông tin tài khoản
                </h5>
            </div>
            <div class="card-body">
                {{-- Lưới 2 cột hiển thị các trường thông tin --}}
                <div class="row g-3">

                    {{-- Mã tài khoản --}}
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-hashtag me-1"></i>Mã tài khoản
                            </small>
                            <span class="fw-bold fs-5">#{{ $user->id }}</span>
                        </div>
                    </div>

                    {{-- Họ và tên --}}
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-user me-1"></i>Họ và tên
                            </small>
                            <span class="fw-bold">{{ $user->name }}</span>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-envelope me-1"></i>Email
                            </small>
                            <span class="fw-bold">{{ $user->email }}</span>
                        </div>
                    </div>

                    {{-- Vai trò --}}
                    <div class="col-md-6">
                        <div class="bg-light rounded-3 p-3">
                            <small class="text-muted d-block mb-1">
                                <i class="fas fa-user-tag me-1"></i>Vai trò
                            </small>
                            @if($user->VaiTro === 'admin')
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-shield-alt me-1"></i>Quản trị viên
                                </span>
                            @elseif($user->VaiTro === 'letan')
                                <span class="badge bg-warning text-dark fs-6">
                                    <i class="fas fa-concierge-bell me-1"></i>Lễ tân
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-user me-1"></i>Khách hàng
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ---- Card 2: Danh sách khách hàng liên kết ---- --}}
        {{-- 
            Một tài khoản user có thể liên kết với nhiều khách hàng
            (quan hệ 1-n: users → khach_hang)
        --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="fas fa-link me-2 text-warning"></i>Khách hàng liên kết
                </h5>
                {{-- Số lượng khách hàng đã liên kết --}}
                <span class="badge bg-primary rounded-pill">{{ $user->khachHang->count() }}</span>
            </div>
            <div class="card-body">
                @if($user->khachHang->count() > 0)
                    {{-- Nếu có khách hàng liên kết → hiển thị danh sách --}}
                    <div class="list-group list-group-flush">
                        @foreach($user->khachHang as $kh)
                            <div class="list-group-item d-flex align-items-center px-0">
                                {{-- Icon khách hàng --}}
                                <div class="rounded-3 bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                     style="width:45px;height:45px;min-width:45px;">
                                    <i class="fas fa-user"></i>
                                </div>
                                {{-- Thông tin khách hàng --}}
                                <div>
                                    <div class="fw-semibold">{{ $kh->TenKhachHang }}</div>
                                    <small class="text-muted">
                                        SĐT: {{ $kh->SoDienThoai ?? 'N/A' }} |
                                        CMND: {{ $kh->GiayChungMinh ?? 'N/A' }}
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    {{-- Nếu chưa có khách hàng liên kết --}}
                    <div class="text-center py-4">
                        <i class="fas fa-unlink fa-3x text-muted mb-3 d-block"></i>
                        <p class="text-muted mb-0">Chưa có khách hàng nào liên kết với tài khoản này</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
