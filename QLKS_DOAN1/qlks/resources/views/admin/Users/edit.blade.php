{{--
    ===================================================================
    TRANG CHỈNH SỬA TÀI KHOẢN
    ===================================================================
    Giao diện Bootstrap 5 thuần
    CHỈ ADMIN MỚI ĐƯỢC VÀO (kiểm tra ở Controller)
    
    Lưu ý quan trọng:
    - Mật khẩu: nếu để trống → giữ nguyên mật khẩu cũ
    - Mật khẩu: nếu nhập mới → đổi mật khẩu
    - Dữ liệu cũ được điền sẵn vào form
--}}

@extends('layout.quanly')

@section('title', 'Chỉnh sửa tài khoản')

@section('content')

{{-- ==================== TIÊU ĐỀ TRANG ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-primary mb-1">
            <i class="fas fa-user-edit me-2"></i>Chỉnh sửa tài khoản
        </h2>
        {{-- Hiển thị tên tài khoản đang sửa --}}
        <p class="text-muted mb-0">Cập nhật thông tin: <strong>{{ $user->name }}</strong></p>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left me-1"></i> Quay lại
    </a>
</div>

{{-- ==================== HIỂN THỊ LỖI VALIDATE ==================== --}}
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

{{-- ==================== FORM CHỈNH SỬA ==================== --}}
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-user-edit me-2 text-warning"></i>Thông tin tài khoản
        </h5>
    </div>

    {{-- 
        Form gửi bằng POST nhưng sử dụng @method('PUT') 
        vì HTML form chỉ hỗ trợ GET và POST
        Laravel dùng trường ẩn _method để nhận biết đây là PUT request
    --}}
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="card-body">

            {{-- ==================== GHI CHÚ VỀ MẬT KHẨU ==================== --}}
            {{-- Thông báo cho admin biết: để trống mật khẩu = không đổi --}}
            <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                <i class="fas fa-info-circle fa-lg me-3"></i>
                <div>
                    <strong>Lưu ý:</strong> Để trống trường mật khẩu nếu bạn không muốn thay đổi mật khẩu hiện tại.
                </div>
            </div>

            <div class="row g-4">

                {{-- ===== Cột trái ===== --}}
                <div class="col-md-6">

                    {{-- Trường: Họ và tên --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Họ và tên <span class="text-danger">*</span>
                        </label>
                        {{-- 
                            old('name', $user->name): ưu tiên lấy giá trị cũ nếu form lỗi,
                            nếu không thì lấy từ database ($user->name)
                        --}}
                        <input type="text" name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $user->name) }}" 
                               placeholder="Nhập họ và tên">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Mật khẩu mới (KHÔNG bắt buộc khi sửa) --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Mật khẩu mới</label>
                        <input type="password" name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Để trống nếu không đổi">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Số điện thoại --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Số điện thoại</label>
                        <input type="text" name="SoDienThoai" 
                               class="form-control @error('SoDienThoai') is-invalid @enderror" 
                               value="{{ old('SoDienThoai', $user->SoDienThoai) }}" 
                               placeholder="Nhập số điện thoại">
                        @error('SoDienThoai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- ===== Cột phải ===== --}}
                <div class="col-md-6">

                    {{-- Trường: Email --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               value="{{ old('email', $user->email) }}" 
                               placeholder="Nhập địa chỉ email">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Trường: Xác nhận mật khẩu mới --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Xác nhận mật khẩu mới</label>
                        <input type="password" name="password_confirmation" 
                               class="form-control" 
                               placeholder="Nhập lại mật khẩu mới">
                    </div>

                    {{-- Trường: Vai trò --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Vai trò <span class="text-danger">*</span>
                        </label>
                        {{-- old() ưu tiên, nếu không có thì lấy $user->VaiTro từ DB --}}
                        <select name="VaiTro" class="form-select @error('VaiTro') is-invalid @enderror">
                            <option value="">-- Chọn vai trò --</option>
                            <option value="admin" {{ old('VaiTro', $user->VaiTro) == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            <option value="letan" {{ old('VaiTro', $user->VaiTro) == 'letan' ? 'selected' : '' }}>Lễ tân</option>
                            <option value="user" {{ old('VaiTro', $user->VaiTro) == 'user' ? 'selected' : '' }}>Khách hàng</option>
                        </select>
                        @error('VaiTro')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-1"></i> Hủy
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save me-1"></i> Cập nhật tài khoản
            </button>
        </div>
    </form>
</div>
@endsection
