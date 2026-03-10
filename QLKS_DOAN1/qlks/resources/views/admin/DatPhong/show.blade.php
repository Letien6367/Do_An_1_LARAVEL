@extends('layout.quanly')

@section('title', 'Chi tiết đặt phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dat-phong.index') }}">Đặt phòng</a></li>
            <li class="breadcrumb-item active">#{{ $datPhong->MaDatPhong }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Chi tiết đặt phòng</h2>
            <p class="text-muted mb-0">Xem thông tin chi tiết đơn đặt phòng</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.dat-phong.edit', $datPhong->MaDatPhong) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Sửa</a>
            <form action="{{ route('admin.dat-phong.destroy', $datPhong->MaDatPhong) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Xóa</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <!-- Header -->
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                            <i class="fas fa-calendar-check fa-2x text-primary"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-1">Đặt phòng #{{ $datPhong->MaDatPhong }}</h4>
                            <div class="d-flex align-items-center gap-3">
                                <small class="text-muted"><i class="fas fa-clock me-1"></i>{{ $datPhong->created_at ? $datPhong->created_at->format('d/m/Y H:i') : 'N/A' }}</small>
                                @php
                                    $statusClass = 'warning';
                                    $statusName = $datPhong->trangThaiDatPhong->TenTrangThaiDP ?? 'N/A';
                                    if (str_contains(strtolower($statusName), 'xác nhận')) $statusClass = 'success';
                                    if (str_contains(strtolower($statusName), 'hủy')) $statusClass = 'danger';
                                    if (str_contains(strtolower($statusName), 'hoàn thành') || str_contains(strtolower($statusName), 'trả')) $statusClass = 'info';
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ $statusName }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Room Info -->
                    <h6 class="fw-bold mb-3"><i class="fas fa-bed text-primary me-2"></i>Thông tin phòng</h6>
                    <div class="card bg-light mb-4">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-3 bg-primary bg-opacity-25 p-3 me-3">
                                <i class="fas fa-bed fa-2x text-primary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $datPhong->phong->TenPhong ?? 'N/A' }}</h5>
                                <p class="text-muted mb-1">{{ $datPhong->phong->loaiPhong->TenLoaiPhong ?? '' }}</p>
                                <div class="d-flex gap-3">
                                    <small class="text-muted"><i class="fas fa-users text-primary me-1"></i>{{ $datPhong->phong->SoNguoi ?? 0 }} người</small>
                                    <small class="text-muted"><i class="fas fa-door-open text-primary me-1"></i>{{ $datPhong->phong->trangThaiPhong->TenTrangThai ?? '' }}</small>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success fs-4">{{ number_format($datPhong->phong->GiaPhong ?? 0, 0, ',', '.') }} VNĐ</span>
                                <br><small class="text-muted">/ đêm</small>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Info -->
                    <h6 class="fw-bold mb-3"><i class="fas fa-user text-primary me-2"></i>Thông tin khách hàng</h6>
                    <div class="card bg-light mb-4">
                        <div class="card-body d-flex align-items-center">
                            <div class="rounded-circle bg-warning bg-opacity-25 d-flex align-items-center justify-content-center me-3" style="width:50px;height:50px;">
                                <span class="fw-bold text-warning fs-5">{{ strtoupper(substr($datPhong->khachHang->TenKhachHang ?? 'K', 0, 1)) }}</span>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">{{ $datPhong->khachHang->TenKhachHang ?? 'N/A' }}</h6>
                                <small class="text-muted d-block"><i class="fas fa-phone me-1"></i>{{ $datPhong->khachHang->SoDienThoai ?? 'N/A' }}</small>
                                <small class="text-muted d-block"><i class="fas fa-id-card me-1"></i>{{ $datPhong->khachHang->GiayChungMinh ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Date Timeline -->
                    <h6 class="fw-bold mb-3"><i class="fas fa-calendar-alt text-primary me-2"></i>Thời gian lưu trú</h6>
                    <div class="d-flex align-items-center justify-content-between bg-light rounded-3 p-3">
                        <div class="text-center border-start border-success border-3 ps-3">
                            <small class="text-muted d-block">Ngày nhận phòng</small>
                            <strong>{{ $datPhong->NgayDatPhong ? $datPhong->NgayDatPhong->format('d/m/Y') : 'N/A' }}</strong>
                        </div>
                        <div class="text-center">
                            <i class="fas fa-arrow-right text-muted mx-2"></i>
                            <span class="badge bg-primary rounded-pill px-3 py-2">{{ $soNgay }} đêm</span>
                            <i class="fas fa-arrow-right text-muted mx-2"></i>
                        </div>
                        <div class="text-center border-start border-danger border-3 ps-3">
                            <small class="text-muted d-block">Ngày trả phòng</small>
                            <strong>{{ $datPhong->NgayTraPhong ? $datPhong->NgayTraPhong->format('d/m/Y') : 'N/A' }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Price Summary -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-receipt text-primary me-2"></i>Chi phí dự kiến</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Giá phòng</span>
                        <span class="fw-semibold">{{ number_format($datPhong->phong->GiaPhong ?? 0, 0, ',', '.') }} VNĐ/đêm</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Số đêm</span>
                        <span class="fw-semibold">{{ $soNgay }} đêm</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 mt-2">
                        <span class="fw-bold">Tổng cộng</span>
                        <span class="fw-bold text-success fs-5">{{ number_format($tongTien, 0, ',', '.') }} VNĐ</span>
                    </div>
                </div>
            </div>

            <!-- Booking Info -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-info-circle text-primary me-2"></i>Thông tin</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <small class="text-muted">Mã đặt phòng</small>
                        <span class="fw-semibold">#{{ $datPhong->MaDatPhong }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <small class="text-muted">Trạng thái</small>
                        <span class="fw-semibold">{{ $datPhong->trangThaiDatPhong->TenTrangThaiDP ?? 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <small class="text-muted">Ngày tạo</small>
                        <span class="fw-semibold">{{ $datPhong->created_at ? $datPhong->created_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2">
                        <small class="text-muted">Cập nhật</small>
                        <span class="fw-semibold">{{ $datPhong->updated_at ? $datPhong->updated_at->format('d/m/Y') : 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="fas fa-bolt text-primary me-2"></i>Thao tác nhanh</h6>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('admin.dat-phong.edit', $datPhong->MaDatPhong) }}" class="btn btn-outline-warning"><i class="fas fa-edit me-2"></i>Sửa đặt phòng</a>
                    <form action="{{ route('admin.dat-phong.destroy', $datPhong->MaDatPhong) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100"><i class="fas fa-trash me-2"></i>Xóa đặt phòng</button>
                    </form>
                    <a href="{{ route('admin.dat-phong.index') }}" class="btn btn-outline-primary"><i class="fas fa-arrow-left me-2"></i>Quay lại danh sách</a>
                </div>
            </div>
        </div>
    </div>
@endsection
