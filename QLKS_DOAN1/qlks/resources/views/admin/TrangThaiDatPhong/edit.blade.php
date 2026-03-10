@extends('layout.quanly')

@section('title', 'Chỉnh sửa trạng thái đặt phòng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Chỉnh sửa trạng thái đặt phòng</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trang-thai-dat-phong.index') }}">Trạng thái đặt phòng</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.trang-thai-dat-phong.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>Vui lòng kiểm tra lại:</strong>
            <ul class="mb-0 mt-1">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-edit me-2 text-warning"></i>Chỉnh sửa trạng thái</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.trang-thai-dat-phong.update', $trangThaiDatPhong->MaTrangThaiDP) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mã trạng thái</label>
                            <input type="text" class="form-control" value="{{ $trangThaiDatPhong->MaTrangThaiDP }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tên trạng thái <span class="text-danger">*</span></label>
                            <input type="text" name="TenTrangThaiDP" class="form-control @error('TenTrangThaiDP') is-invalid @enderror" value="{{ old('TenTrangThaiDP', $trangThaiDatPhong->TenTrangThaiDP) }}" placeholder="Nhập tên trạng thái đặt phòng...">
                            @error('TenTrangThaiDP')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.trang-thai-dat-phong.index') }}" class="btn btn-light">Hủy</a>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
