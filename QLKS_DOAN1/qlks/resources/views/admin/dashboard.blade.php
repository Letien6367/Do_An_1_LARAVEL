@extends('layout.quanly')

@section('title', 'Dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Xin chào, {{ Auth::user()->name ?? 'Admin' }}!</h2>
            <p class="text-muted mb-0">Chào mừng bạn quay lại. Dưới đây là tổng quan hệ thống.</p>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                        <i class="fas fa-bed fa-2x text-primary"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $tongPhong ?? 0 }}</h3>
                        <small class="text-muted">Tổng số phòng</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-check fa-2x text-success"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $tongDatPhong ?? 0 }}</h3>
                        <small class="text-muted">Đặt phòng hôm nay</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-users fa-2x text-warning"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ $tongKhachHang ?? 0 }}</h3>
                        <small class="text-muted">Khách hàng</small>
                        @if(isset($phanTramKhachHang) && $phanTramKhachHang != 0)
                            <div class="small {{ $phanTramKhachHang >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-arrow-{{ $phanTramKhachHang >= 0 ? 'up' : 'down' }}"></i>
                                {{ abs($phanTramKhachHang) }}% so với tuần trước
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-info border-4 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-money-bill-wave fa-2x text-info"></i>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-0">{{ number_format($doanhThu ?? 0, 0, ',', '.') }}</h3>
                        <small class="text-muted">Doanh thu tháng (VNĐ)</small>
                        @if(isset($phanTramDoanhThu) && $phanTramDoanhThu != 0)
                            <div class="small {{ $phanTramDoanhThu >= 0 ? 'text-success' : 'text-danger' }}">
                                <i class="fas fa-arrow-{{ $phanTramDoanhThu >= 0 ? 'up' : 'down' }}"></i>
                                {{ abs($phanTramDoanhThu) }}% so với tháng trước
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3"><i class="fas fa-bolt text-warning me-2"></i>Truy cập nhanh</h5>
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.phong.index') }}" class="card shadow-sm text-decoration-none h-100 border-0">
                <div class="card-body text-center py-4">
                    <i class="fas fa-bed fa-3x text-primary mb-3"></i>
                    <h6 class="fw-bold text-dark">Quản lý phòng</h6>
                    <small class="text-muted">{{ $tongPhong ?? 0 }} phòng</small>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.loai-phong.index') }}" class="card shadow-sm text-decoration-none h-100 border-0">
                <div class="card-body text-center py-4">
                    <i class="fas fa-layer-group fa-3x text-success mb-3"></i>
                    <h6 class="fw-bold text-dark">Loại phòng</h6>
                    <small class="text-muted">{{ $tongLoaiPhong ?? 0 }} loại</small>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ route('admin.trang-thai-phong.index') }}" class="card shadow-sm text-decoration-none h-100 border-0">
                <div class="card-body text-center py-4">
                    <i class="fas fa-toggle-on fa-3x text-warning mb-3"></i>
                    <h6 class="fw-bold text-dark">Trạng thái phòng</h6>
                    <small class="text-muted">{{ $tongTrangThai ?? 0 }} trạng thái</small>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-md-6">
            <a href="{{ url('/admin/khach-hang') }}" class="card shadow-sm text-decoration-none h-100 border-0">
                <div class="card-body text-center py-4">
                    <i class="fas fa-user-plus fa-3x text-info mb-3"></i>
                    <h6 class="fw-bold text-dark">Khách hàng</h6>
                    <small class="text-muted">{{ $tongKhachHang ?? 0 }} khách</small>
                </div>
            </a>
        </div>
    </div>
@endsection
