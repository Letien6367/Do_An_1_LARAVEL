@extends('layout.quanly')

@section('title', 'Thêm loại phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.loai-phong.index') }}">Loại phòng</a></li>
            <li class="breadcrumb-item active">Thêm mới</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Thêm loại phòng mới</h2>
        <p class="text-muted mb-0">Nhập thông tin loại phòng</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm" style="max-width: 600px;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-layer-group text-primary me-2"></i>Thông tin loại phòng</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.loai-phong.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tên loại phòng <span class="text-danger">*</span></label>
                    <input type="text" name="TenLoaiPhong" class="form-control" value="{{ old('TenLoaiPhong') }}" required placeholder="VD: Phòng VIP, Phòng Standard...">
                    @error('TenLoaiPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Mô tả</label>
                    <textarea name="MoTa" class="form-control" rows="3" placeholder="Mô tả chi tiết về loại phòng...">{{ old('MoTa') }}</textarea>
                    @error('MoTa')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="d-flex justify-content-end gap-2 pt-3 border-top">
                    <a href="{{ route('admin.loai-phong.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Lưu</button>
                </div>
            </form>
        </div>
    </div>
@endsection
