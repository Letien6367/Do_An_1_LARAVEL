@extends('layout.quanly')

@section('title', 'Sửa loại phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.loai-phong.index') }}">Loại phòng</a></li>
            <li class="breadcrumb-item active">Sửa {{ $loaiPhong->TenLoaiPhong }}</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Sửa loại phòng</h2>
        <p class="text-muted mb-0">Cập nhật thông tin loại phòng</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-edit text-warning me-2"></i>Thông tin loại phòng</h5>
            <span class="badge bg-secondary">ID: #{{ $loaiPhong->MaLoaiPhong }}</span>
        </div>
        <div class="card-body">
            <div class="alert alert-light border mb-3">
                <div class="row">
                    <div class="col-md-6"><small class="text-muted">Số phòng:</small> <strong>{{ $loaiPhong->phong->count() ?? 0 }}</strong></div>
                    <div class="col-md-6"><small class="text-muted">Ngày tạo:</small> <strong>{{ $loaiPhong->created_at ? $loaiPhong->created_at->format('d/m/Y') : 'N/A' }}</strong></div>
                </div>
            </div>

            <form action="{{ route('admin.loai-phong.update', $loaiPhong->MaLoaiPhong) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên loại phòng <span class="text-danger">*</span></label>
                    <input type="text" name="TenLoaiPhong" class="form-control" value="{{ old('TenLoaiPhong', $loaiPhong->TenLoaiPhong) }}" required>
                    @error('TenLoaiPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="MoTa" class="form-control" rows="3">{{ old('MoTa', $loaiPhong->MoTa ?? '') }}</textarea>
                    @error('MoTa')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('admin.loai-phong.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection
