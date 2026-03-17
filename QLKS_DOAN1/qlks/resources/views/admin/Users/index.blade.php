{{--
    ===================================================================
    TRANG DANH SÁCH TÀI KHOẢN NGƯỜI DÙNG
    ===================================================================
    Giao diện sử dụng Bootstrap 5 thuần (không CSS custom)
    CHỈ ADMIN MỚI ĐƯỢC VÀO TRANG NÀY (đã kiểm tra ở Controller)
    
    Chức năng:
    - Hiển thị bảng danh sách tất cả tài khoản
    - Tìm kiếm theo tên, email, SĐT
    - Lọc theo vai trò (admin / lễ tân / khách hàng)
    - Thống kê số lượng từng loại vai trò
    - Thêm / Sửa / Xóa / Xem chi tiết tài khoản
--}}

@extends('layout.quanly')

@section('title', 'Quản lý tài khoản')

@section('content')

{{-- ==================== TIÊU ĐỀ TRANG + NÚT THÊM MỚI ==================== --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        {{-- Tiêu đề chính --}}
        <h2 class="fw-bold text-primary mb-1">
            <i class="fas fa-users-cog me-2"></i>Quản lý tài khoản
        </h2>
        {{-- Mô tả phụ --}}
        <p class="text-muted mb-0">Quản lý tài khoản người dùng trong hệ thống khách sạn</p>
    </div>
    {{-- Nút thêm tài khoản mới --}}
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Thêm tài khoản
    </a>
</div>

{{-- ==================== THÔNG BÁO KẾT QUẢ ==================== --}}
{{-- Hiển thị khi thêm/sửa/xóa thành công --}}
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Hiển thị khi có lỗi (VD: không thể xóa) --}}
@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ==================== 4 THẺ THỐNG KÊ ĐẦU TRANG ==================== --}}
{{-- Hiển thị tổng số tài khoản và số lượng theo từng vai trò --}}
<div class="row g-3 mb-4">

    {{-- Thẻ 1: Tổng tài khoản --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3">
                    <i class="fas fa-users fa-2x text-primary"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $tongUser }}</h3>
                    <small class="text-muted">Tổng tài khoản</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Thẻ 2: Số quản trị viên (admin) --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-danger bg-opacity-10 p-3 me-3">
                    <i class="fas fa-user-shield fa-2x text-danger"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $soAdmin }}</h3>
                    <small class="text-muted">Quản trị viên</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Thẻ 3: Số lễ tân --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3">
                    <i class="fas fa-user-tie fa-2x text-warning"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $soLeTan }}</h3>
                    <small class="text-muted">Lễ tân</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Thẻ 4: Số khách hàng (user thường) --}}
    <div class="col-xl-3 col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3">
                    <i class="fas fa-user fa-2x text-success"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0">{{ $soKhachHang }}</h3>
                    <small class="text-muted">Khách hàng</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== THANH TÌM KIẾM + LỌC VAI TRÒ ==================== --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        {{-- Form tìm kiếm: gửi bằng GET để giữ dữ liệu trên URL --}}
        <form action="{{ route('admin.users.index') }}" method="GET">
            <div class="row g-3 align-items-end">

                {{-- Ô nhập từ khóa tìm kiếm --}}
                <div class="col-md-5">
                    <label class="form-label fw-semibold">Tìm kiếm</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" 
                               value="{{ request('search') }}" 
                               placeholder="Nhập tên, email hoặc SĐT...">
                    </div>
                </div>

                {{-- Dropdown lọc theo vai trò --}}
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Vai trò</label>
                    <select name="vai_tro" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tất cả vai trò --</option>
                        <option value="admin" {{ request('vai_tro') == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                        <option value="letan" {{ request('vai_tro') == 'letan' ? 'selected' : '' }}>Lễ tân</option>
                        <option value="KhachHang" {{ in_array(request('vai_tro'), ['KhachHang', 'user']) ? 'selected' : '' }}>Khách hàng</option>
                    </select>
                </div>

                {{-- Nút tìm kiếm --}}
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-search me-1"></i> Tìm kiếm
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- ==================== BẢNG DANH SÁCH TÀI KHOẢN ==================== --}}
<div class="card border-0 shadow-sm">

    {{-- Header bảng: tiêu đề + tổng số bản ghi --}}
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 fw-bold">
            <i class="fas fa-list me-2 text-primary"></i>Danh sách tài khoản
        </h5>
        <span class="badge bg-primary rounded-pill">Tổng: {{ $users->total() }}</span>
    </div>

    <div class="card-body p-0">
        {{-- Nếu có dữ liệu thì hiển thị bảng --}}
        @if($users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    {{-- Dòng tiêu đề cột --}}
                    <thead class="table-light">
                        <tr>
                            <th style="width:60px;">ID</th>
                            <th>Thông tin người dùng</th>
                            <th>Số điện thoại</th>
                            <th>Vai trò</th>
                            <th>Ngày tạo</th>
                            <th class="text-center" style="width:150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- Vòng lặp: hiển thị từng tài khoản thành 1 dòng --}}
                        @foreach($users as $user)
                        <tr>
                            {{-- Cột: Mã ID --}}
                            <td><strong>#{{ $user->id }}</strong></td>

                            {{-- Cột: Avatar tròn + Tên + Email --}}
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- 
                                        Avatar tròn: lấy chữ cái đầu của tên
                                        Màu nền thay đổi theo vai trò:
                                        - admin = đỏ (bg-danger)
                                        - letan = vàng (bg-warning)  
                                        - user  = xanh dương (bg-primary)
                                    --}}
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold text-white
                                        {{ $user->VaiTro === 'admin' ? 'bg-danger' : ($user->VaiTro === 'letan' ? 'bg-warning' : 'bg-primary') }}"
                                        style="width:40px;height:40px;min-width:40px;">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold">{{ $user->name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Cột: Số điện thoại --}}
                            <td>
                                @if($user->SoDienThoai)
                                    <i class="fas fa-phone text-success me-1"></i>{{ $user->SoDienThoai }}
                                @else
                                    <span class="text-muted fst-italic">Chưa cập nhật</span>
                                @endif
                            </td>

                            {{-- 
                                Cột: Vai trò - Hiển thị badge với màu khác nhau
                                - admin = badge đỏ
                                - letan = badge vàng
                                - user  = badge xanh lá
                            --}}
                            <td>
                                @if($user->VaiTro === 'admin')
                                    <span class="badge bg-danger">
                                        <i class="fas fa-shield-alt me-1"></i>Quản trị viên
                                    </span>
                                @elseif($user->VaiTro === 'letan')
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-concierge-bell me-1"></i>Lễ tân
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-user me-1"></i>Khách hàng
                                    </span>
                                @endif
                            </td>

                            {{-- Cột: Ngày tạo tài khoản --}}
                            <td>
                                <small>{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}</small>
                            </td>

                            {{-- 
                                Cột: Các nút thao tác
                                Sử dụng btn-group của Bootstrap để gom nút lại
                            --}}
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    {{-- Nút xem chi tiết (màu xanh info) --}}
                                    <a href="{{ route('admin.users.show', $user->id) }}" 
                                       class="btn btn-outline-info" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    {{-- Nút chỉnh sửa (màu vàng warning) --}}
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-outline-warning" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    {{-- 
                                        Nút xóa (màu đỏ danger)
                                        KHÔNG hiển thị nút xóa cho chính tài khoản đang đăng nhập
                                        Có hộp thoại xác nhận trước khi xóa
                                    --}}
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" 
                                              style="display:inline;" 
                                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa tài khoản {{ $user->name }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ==================== PHÂN TRANG ==================== --}}
            {{-- Hiển thị thông tin "X - Y trong tổng Z" và nút chuyển trang --}}
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    Hiển thị {{ $users->firstItem() }} - {{ $users->lastItem() }} 
                    trong tổng số {{ $users->total() }} tài khoản
                </small>
                {{-- appends() giữ lại tham số search/vai_tro khi chuyển trang --}}
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else

            {{-- ==================== TRẠNG THÁI RỖNG ==================== --}}
            {{-- Hiển thị khi không có dữ liệu hoặc tìm kiếm không có kết quả --}}
            <div class="text-center py-5">
                <i class="fas fa-users-slash fa-4x text-muted mb-3 d-block"></i>
                <h5 class="text-muted">Chưa có tài khoản nào</h5>
                <p class="text-muted">Bắt đầu thêm tài khoản đầu tiên cho hệ thống</p>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Thêm tài khoản
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
