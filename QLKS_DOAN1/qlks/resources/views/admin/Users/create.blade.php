{{--
    ===================================================================
    TRANG THÊM TÀI KHOẢN MỚI
    ===================================================================
    Giao diện Bootstrap 5 thuần
    CHỈ ADMIN MỚI ĐƯỢC VÀO (kiểm tra ở Controller)
    
    Các trường nhập:
    - Họ tên (bắt buộc)
    - Email (bắt buộc, duy nhất)
    - Mật khẩu + Xác nhận mật khẩu (bắt buộc, ít nhất 6 ký tự)
    - Số điện thoại (không bắt buộc)
    - Vai trò (bắt buộc: admin / letan / user)
--}}

@extends('layout.quanly')

@section('title', 'Thêm tài khoản mới')

@section('content')

{{-- ==================== TIÊU ĐỀ TRANG + NÚT QUAY LẠI ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">
            <i class="fas fa-user-plus me-2"></i>Thêm tài khoản mới
        </h2>
        <p class="text-muted mb-0">Tạo tài khoản người dùng mới trong hệ thống</p>
    </div>
    {{-- Nút quay lại danh sách --}}
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

{{-- ==================== HIỂN THỊ LỖI VALIDATE ==================== --}}
{{-- 
    Khi người dùng nhập sai (VD: email trùng, mật khẩu quá ngắn)
    Laravel sẽ trả về lỗi → hiển thị ở đây
--}}
@if($errors->any())
    <div class="alert alert-danger">
        <i class="fas fa-exclamation-triangle me-2"></i><strong>Có lỗi xảy ra:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ==================== FORM THÊM TÀI KHOẢN ==================== --}}
<div class="card border-0 shadow-sm">
    {{-- Header card --}}
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-user-plus me-2 text-warning"></i>Thông tin tài khoản
        </h5>
    </div>

    {{-- 
        Form gửi bằng POST đến route admin.users.store
        @csrf: Token chống tấn công giả mạo (bắt buộc trong Laravel)
    --}}
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="row g-4">

                {{-- ===== Cột trái: Họ tên + Mật khẩu + SĐT ===== --}}
                <div class="col-md-6">

                    {{-- Trường: Họ và tên (bắt buộc) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Họ và tên <span class="text-danger">*</span>
                        </label>
                        {{-- 
                            old('name'): giữ lại giá trị đã nhập nếu form bị lỗi
                            @error: viền đỏ nếu trường có lỗi
                        --}}
                        <input type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" 
                               placeholder="Nhập họ và tên">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Mật khẩu (bắt buộc, tối thiểu 6 ký tự) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Mật khẩu <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Ít nhất 6 ký tự">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Số điện thoại (không bắt buộc) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="SoDienThoai" 
                               class="form-control @error('SoDienThoai') is-invalid @enderror" 
                               value="{{ old('SoDienThoai') }}" 
                               placeholder="Nhập số điện thoại (không bắt buộc)">
                        @error('SoDienThoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ===== Cột phải: Email + Xác nhận MK + Vai trò ===== --}}
                <div class="col-md-6">

                    {{-- Trường: Email (bắt buộc, phải duy nhất trong hệ thống) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email') }}" 
                               placeholder="Nhập địa chỉ email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Xác nhận mật khẩu (phải giống mật khẩu ở trên) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Xác nhận mật khẩu <span class="text-danger">*</span>
                        </label>
                        <input type="password" name="password_confirmation" 
                               class="form-control" 
                               placeholder="Nhập lại mật khẩu">
                    </div>

                    {{-- 
                        Trường: Vai trò (bắt buộc, chọn 1 trong 3)
                        - admin: Quản trị viên - toàn quyền
                        - letan: Lễ tân - quản lý đặt phòng, khách hàng
                        - user:  Khách hàng - đặt phòng trực tuyến
                    --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Vai trò <span class="text-danger">*</span>
                        </label>
                        <select name="VaiTro" class="form-select @error('VaiTro') is-invalid @enderror">
                            <option value="">-- Chọn vai trò --</option>
                            <option value="admin" {{ old('VaiTro') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="letan" {{ old('VaiTro') == 'letan' ? 'selected' : '' }}>Lễ tân</option>
                            <option value="user" {{ old('VaiTro') == 'user' ? 'selected' : '' }}>Khách hàng</option>
                        </select>
                        @error('VaiTro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        {{-- Chú thích giải nghĩa từng vai trò --}}
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Admin:</strong> Toàn quyền |
                            <strong>Lễ tân:</strong> Quản lý đặt phòng |
                            <strong>Khách hàng:</strong> Đặt phòng online
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ==================== CÁC NÚT Ở CHÂN FORM ==================== --}}
        <div class="card-footer bg-light d-flex justify-content-end gap-2 py-3">
            {{-- Nút Hủy: quay lại danh sách --}}
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i> Hủy
            </a>
            {{-- Nút Lưu: gửi form --}}
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Lưu tài khoản
            </button>
        </div>
    </form>
</div>
@endsection
