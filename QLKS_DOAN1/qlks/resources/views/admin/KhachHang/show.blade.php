@extends('layout.quanly')

@section('title', 'Chi tiết khách hàng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.khach-hang.index') }}">Khách hàng</a></li>
            <li class="breadcrumb-item active">{{ $khachHang->TenKhachHang }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Chi tiết khách hàng</h2>
        <a href="{{ route('admin.khach-hang.edit', $khachHang->MaKhachHang) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Sửa</a>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm text-center">
                <div class="card-body py-4">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px;">
                        <span class="fw-bold text-primary fs-1">{{ strtoupper(substr($khachHang->TenKhachHang, 0, 1)) }}</span>
                    </div>
                    <h4 class="fw-bold">{{ $khachHang->TenKhachHang }}</h4>
                    <span class="badge bg-secondary mb-3">Mã: #{{ $khachHang->MaKhachHang }}</span>

                    <div class="text-start mt-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-phone me-1"></i> SĐT</small>
                            <span class="fw-semibold">{{ $khachHang->SoDienThoai ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-id-card me-1"></i> CMND</small>
                            <span class="fw-semibold">{{ $khachHang->GiayChungMinh ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-birthday-cake me-1"></i> Sinh</small>
                            <span class="fw-semibold">{{ $khachHang->NgaySinh ? \Carbon\Carbon::parse($khachHang->NgaySinh)->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-map-marker-alt me-1"></i> Địa chỉ</small>
                            <span class="fw-semibold">{{ $khachHang->DiaChi ?? 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> Tạo</small>
                            <span class="fw-semibold">{{ $khachHang->created_at ? $khachHang->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>

                    @if($khachHang->user)
                        <div class="alert alert-info mt-3 text-start mb-0">
                            <small class="fw-bold"><i class="fas fa-user-circle me-1"></i> Tài khoản liên kết</small><br>
                            <small>{{ $khachHang->user->name }} ({{ $khachHang->user->email }})</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history text-primary me-2"></i>Lịch sử đặt phòng</h5>
                </div>
                <div class="card-body p-0">
                    @if($khachHang->datPhong && $khachHang->datPhong->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Phòng</th>
                                        <th>Ngày nhận</th>
                                        <th>Ngày trả</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($khachHang->datPhong as $dp)
                                        <tr>
                                            <td class="ps-3 fw-semibold">{{ $dp->phong->SoPhong ?? $dp->phong->TenPhong ?? 'N/A' }}</td>
                                            <td>{{ $dp->NgayNhanPhong ?? ($dp->NgayDatPhong ? $dp->NgayDatPhong->format('d/m/Y') : 'N/A') }}</td>
                                            <td>{{ $dp->NgayTraPhong ? $dp->NgayTraPhong->format('d/m/Y') : 'N/A' }}</td>
                                            <td><span class="badge bg-info">{{ $dp->trangThaiDatPhong->TenTrangThaiDP ?? $dp->trangThaiDatPhong->TenTrangThai ?? 'N/A' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có lịch sử đặt phòng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
