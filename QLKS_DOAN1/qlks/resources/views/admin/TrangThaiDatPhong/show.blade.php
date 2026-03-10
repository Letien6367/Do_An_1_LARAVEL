@extends('layout.quanly')

@section('title', 'Chi tiết trạng thái đặt phòng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Chi tiết trạng thái đặt phòng</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trang-thai-dat-phong.index') }}">Trạng thái đặt phòng</a></li>
                    <li class="breadcrumb-item active">{{ $trangThaiDatPhong->TenTrangThaiDP }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trang-thai-dat-phong.edit', $trangThaiDatPhong->MaTrangThaiDP) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Sửa</a>
            <a href="{{ route('admin.trang-thai-dat-phong.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-info"></i>Thông tin</h5>
                </div>
                <div class="card-body text-center">
                    <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                        <i class="fas fa-flag fa-2x text-info"></i>
                    </div>
                    <h4 class="fw-bold">{{ $trangThaiDatPhong->TenTrangThaiDP }}</h4>
                    <p class="text-muted mb-3">Mã: {{ $trangThaiDatPhong->MaTrangThaiDP }}</p>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">{{ $tongDatPhong ?? 0 }}</h5>
                            <small class="text-muted">Tổng đặt phòng</small>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">{{ $tyLe ?? 0 }}%</h5>
                            <small class="text-muted">Tỷ lệ</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-check me-2 text-success"></i>Đặt phòng thuộc trạng thái này</h5>
                    <span class="badge bg-info">{{ $tongDatPhong ?? 0 }} đặt phòng</span>
                </div>
                <div class="card-body p-0">
                    @if(isset($trangThaiDatPhong->datPhong) && $trangThaiDatPhong->datPhong->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">STT</th>
                                        <th>Mã đặt phòng</th>
                                        <th>Khách hàng</th>
                                        <th>Phòng</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trangThaiDatPhong->datPhong as $index => $dp)
                                        <tr>
                                            <td class="ps-3">{{ $index + 1 }}</td>
                                            <td><span class="badge bg-light text-dark border">{{ $dp->MaDatPhong }}</span></td>
                                            <td>{{ $dp->khachHang->TenKhachHang ?? 'N/A' }}</td>
                                            <td>{{ $dp->phong->TenPhong ?? 'N/A' }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.dat-phong.show', $dp->MaDatPhong) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có đặt phòng nào thuộc trạng thái này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
