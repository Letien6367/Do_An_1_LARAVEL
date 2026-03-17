@extends('layout.quanly')

@section('title', 'Thêm phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.phong.index') }}">Phòng</a></li>
            <li class="breadcrumb-item active">Thêm phòng</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Thêm phòng mới</h2>
        <p class="text-muted mb-0">Nhập thông tin để tạo phòng trong hệ thống</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm" style="max-width: 800px;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-plus-circle text-primary me-2"></i>Thông tin phòng</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.phong.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên phòng <span class="text-danger">*</span></label>
                        <input type="text" name="TenPhong" class="form-control" value="{{ old('TenPhong') }}" required>
                        @error('TenPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Loại phòng <span class="text-danger">*</span></label>
                        <select name="MaLoaiPhong" class="form-select" required>
                            <option value="">-- Chọn loại phòng --</option>
                            @foreach($loaiPhongs as $lp)
                                <option value="{{ $lp->MaLoaiPhong }}" {{ old('MaLoaiPhong') == $lp->MaLoaiPhong ? 'selected' : '' }}>{{ $lp->TenLoaiPhong }}</option>
                            @endforeach
                        </select>
                        @error('MaLoaiPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Số người <span class="text-danger">*</span></label>
                        <input type="number" name="SoNguoi" class="form-control" value="{{ old('SoNguoi') }}" min="1" max="10" required>
                        @error('SoNguoi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giá phòng (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="GiaPhong" class="form-control" value="{{ old('GiaPhong') }}" min="0" required>
                        @error('GiaPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <select name="MaTrangThai" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach($trangThais as $tt)
                                <option value="{{ $tt->MaTrangThai }}" {{ old('MaTrangThai') == $tt->MaTrangThai ? 'selected' : '' }}>{{ $tt->TenTrangThai }}</option>
                            @endforeach
                        </select>
                        @error('MaTrangThai')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Lưu phòng</button>
                </div>
            </form>
        </div>
    </div>
@endsection
