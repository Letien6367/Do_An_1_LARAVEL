@extends('layout.quanly')

@section('title', 'Chi tiết loại phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.loai-phong.index') }}">Loại phòng</a></li>
            <li class="breadcrumb-item active">{{ $loaiPhong->TenLoaiPhong }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Chi tiết loại phòng</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.loai-phong.edit', $loaiPhong->MaLoaiPhong) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Sửa</a>
            <form action="{{ route('admin.loai-phong.destroy', $loaiPhong->MaLoaiPhong) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Xóa</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="rounded-3 bg-success bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:70px;height:70px;">
                        <i class="fas fa-layer-group fa-2x text-success"></i>
                    </div>
                    <h4 class="fw-bold">{{ $loaiPhong->TenLoaiPhong }}</h4>
                    <span class="badge bg-secondary mb-3">Mã: #{{ $loaiPhong->MaLoaiPhong }}</span>

                    <div class="row g-3 mt-2">
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-2">
                                <h5 class="fw-bold text-primary mb-0">{{ $tongPhong ?? 0 }}</h5>
                                <small class="text-muted">Tổng phòng</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-2">
                                <h5 class="fw-bold text-success mb-0">{{ $phongTrong ?? 0 }}</h5>
                                <small class="text-muted">Trống</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="bg-light rounded-3 p-2">
                                <h5 class="fw-bold text-danger mb-0">{{ $phongDangSD ?? 0 }}</h5>
                                <small class="text-muted">Đang SD</small>
                            </div>
                        </div>
                    </div>

                    <div class="text-start mt-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted">Ngày tạo</small>
                            <span class="fw-semibold">{{ $loaiPhong->created_at ? $loaiPhong->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <small class="text-muted">Cập nhật</small>
                            <span class="fw-semibold">{{ $loaiPhong->updated_at ? $loaiPhong->updated_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bed text-primary me-2"></i>Danh sách phòng</h5>
                    <a href="{{ route('admin.phong.create') }}" class="btn btn-sm btn-primary"><i class="fas fa-plus me-1"></i> Thêm phòng</a>
                </div>
                <div class="card-body p-0">
                    @if($loaiPhong->phong && $loaiPhong->phong->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Tên phòng</th>
                                        <th>Giá phòng</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($loaiPhong->phong as $phong)
                                        <tr>
                                            <td class="ps-3 fw-semibold">{{ $phong->TenPhong }}</td>
                                            <td class="text-success fw-semibold">{{ number_format($phong->GiaPhong, 0, ',', '.') }} VNĐ</td>
                                            <td><span class="badge bg-info">{{ $phong->trangThaiPhong->TenTrangThai ?? 'N/A' }}</span></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có phòng nào thuộc loại này</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
