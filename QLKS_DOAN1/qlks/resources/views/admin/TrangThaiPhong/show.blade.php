@extends('layout.quanly')

@section('title', 'Chi tiết trạng thái phòng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Chi tiết trạng thái phòng</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trang-thai-phong.index') }}">Trạng thái phòng</a></li>
                    <li class="breadcrumb-item active">{{ $trangThaiPhong->TenTrangThai }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.trang-thai-phong.edit', $trangThaiPhong->MaTrangThai) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Sửa</a>
            <a href="{{ route('admin.trang-thai-phong.index') }}" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-info-circle me-2 text-primary"></i>Thông tin</h5>
                </div>
                <div class="card-body text-center">
                    <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex align-items-center justify-content-center mb-3" style="width:80px;height:80px;">
                        <i class="fas fa-toggle-on fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold">{{ $trangThaiPhong->TenTrangThai }}</h4>
                    <p class="text-muted mb-3">Mã: {{ $trangThaiPhong->MaTrangThai }}</p>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">{{ $tongPhong ?? 0 }}</h5>
                            <small class="text-muted">Tổng phòng</small>
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
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bed me-2 text-success"></i>Phòng thuộc trạng thái này</h5>
                    <span class="badge bg-primary">{{ $tongPhong ?? 0 }} phòng</span>
                </div>
                <div class="card-body p-0">
                    @if(isset($trangThaiPhong->phong) && $trangThaiPhong->phong->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">STT</th>
                                        <th>Tên phòng</th>
                                        <th>Loại phòng</th>
                                        <th>Giá phòng</th>
                                        <th class="text-center">Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($trangThaiPhong->phong as $index => $phong)
                                        <tr>
                                            <td class="ps-3">{{ $index + 1 }}</td>
                                            <td class="fw-semibold">{{ $phong->TenPhong }}</td>
                                            <td>{{ $phong->loaiPhong->TenLoaiPhong ?? 'N/A' }}</td>
                                            <td class="text-success fw-semibold">{{ number_format($phong->GiaPhong) }}đ</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.phong.show', $phong->MaPhong) }}" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có phòng nào thuộc trạng thái này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
