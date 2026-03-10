@extends('layout.quanly')

@section('title', 'Sửa phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.phong.index') }}">Phòng</a></li>
            <li class="breadcrumb-item active">Sửa {{ $phong->TenPhong }}</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Sửa phòng</h2>
        <p class="text-muted mb-0">Cập nhật thông tin phòng</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm" style="max-width: 800px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-edit text-warning me-2"></i>Thông tin phòng</h5>
            <span class="badge bg-secondary">ID: #{{ $phong->MaPhong }}</span>
        </div>
        <div class="card-body">
            <div class="alert alert-light border mb-4">
                <div class="row">
                    <div class="col-md-6"><small class="text-muted">Ngày tạo:</small> <strong>{{ $phong->created_at ? $phong->created_at->format('d/m/Y') : 'N/A' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted">Cập nhật:</small> <strong>{{ $phong->updated_at ? $phong->updated_at->format('d/m/Y') : 'N/A' }}</strong></div>
                </div>
            </div>

            <form action="{{ route('admin.phong.update', $phong->MaPhong) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên phòng <span class="text-danger">*</span></label>
                        <input type="text" name="TenPhong" class="form-control" value="{{ old('TenPhong', $phong->TenPhong) }}" required>
                        @error('TenPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Loại phòng <span class="text-danger">*</span></label>
                        <select name="MaLoaiPhong" class="form-select" required>
                            <option value="">-- Chọn loại phòng --</option>
                            @foreach($loaiPhongs as $lp)
                                <option value="{{ $lp->MaLoaiPhong }}" {{ old('MaLoaiPhong', $phong->MaLoaiPhong) == $lp->MaLoaiPhong ? 'selected' : '' }}>{{ $lp->TenLoaiPhong }}</option>
                            @endforeach
                        </select>
                        @error('MaLoaiPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Số người <span class="text-danger">*</span></label>
                        <input type="number" name="SoNguoi" class="form-control" value="{{ old('SoNguoi', $phong->SoNguoi) }}" min="1" required>
                        @error('SoNguoi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Giá phòng (VNĐ) <span class="text-danger">*</span></label>
                        <input type="number" name="GiaPhong" class="form-control" value="{{ old('GiaPhong', $phong->GiaPhong) }}" min="0" required>
                        @error('GiaPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <select name="MaTrangThai" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach($trangThais as $tt)
                                <option value="{{ $tt->MaTrangThai }}" {{ old('MaTrangThai', $phong->MaTrangThai) == $tt->MaTrangThai ? 'selected' : '' }}>{{ $tt->TenTrangThai }}</option>
                            @endforeach
                        </select>
                        @error('MaTrangThai')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.phong.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection
