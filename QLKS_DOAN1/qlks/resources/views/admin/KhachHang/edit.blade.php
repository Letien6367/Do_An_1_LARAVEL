@extends('layout.quanly')

@section('title', 'Sửa khách hàng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.khach-hang.index') }}">Khách hàng</a></li>
            <li class="breadcrumb-item active">Sửa #{{ $khachHang->MaKhachHang }}</li>
        </ol>
    </nav>

    <div class="mb-4">
        <h2 class="fw-bold text-dark mb-1">Sửa khách hàng</h2>
        <p class="text-muted mb-0">Cập nhật thông tin khách hàng</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <div class="card shadow-sm" style="max-width: 800px;">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-user-edit text-warning me-2"></i>Thông tin khách hàng</h5>
            <span class="badge bg-secondary">ID: #{{ $khachHang->MaKhachHang }}</span>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.khach-hang.update', $khachHang->MaKhachHang) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tên khách hàng <span class="text-danger">*</span></label>
                        <input type="text" name="TenKhachHang" class="form-control" value="{{ old('TenKhachHang', $khachHang->TenKhachHang) }}" required>
                        @error('TenKhachHang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày sinh</label>
                        <input type="date" name="NgaySinh" class="form-control" value="{{ old('NgaySinh', $khachHang->NgaySinh) }}">
                        @error('NgaySinh')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="SoDienThoai" class="form-control" value="{{ old('SoDienThoai', $khachHang->SoDienThoai) }}">
                        @error('SoDienThoai')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">CMND/CCCD</label>
                        <input type="text" name="GiayChungMinh" class="form-control" value="{{ old('GiayChungMinh', $khachHang->GiayChungMinh) }}">
                        @error('GiayChungMinh')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Địa chỉ</label>
                        <textarea name="DiaChi" class="form-control" rows="2">{{ old('DiaChi', $khachHang->DiaChi) }}</textarea>
                        @error('DiaChi')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Tài khoản liên kết</label>
                        <select name="MaTaiKhoan" class="form-select">
                            <option value="">-- Không liên kết --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('MaTaiKhoan', $khachHang->MaTaiKhoan) == $user->id ? 'selected' : '' }}>{{ $user->name }} - {{ $user->email }}</option>
                            @endforeach
                        </select>
                        @error('MaTaiKhoan')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.khach-hang.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection
