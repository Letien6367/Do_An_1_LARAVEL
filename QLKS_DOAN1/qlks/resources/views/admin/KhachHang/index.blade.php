@extends('layout.quanly')

@section('title', 'Quản lý khách hàng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý khách hàng</h2>
            <p class="text-muted mb-0">Quản lý danh sách khách hàng của khách sạn</p>
        </div>
        <a href="{{ route('admin.khach-hang.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm khách hàng
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3"><i class="fas fa-users fa-lg text-primary"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $tongKhachHang ?? 0 }}</h4><small class="text-muted">Tổng khách hàng</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3"><i class="fas fa-user-plus fa-lg text-success"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $khachHangMoi ?? 0 }}</h4><small class="text-muted">Khách hàng mới</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.khach-hang.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small text-muted">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên, SĐT, CMND..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Danh sách khách hàng</h5>
        </div>
        <div class="card-body p-0">
            @if(isset($khachHangs) && $khachHangs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">Mã KH</th>
                                <th>Thông tin khách hàng</th>
                                <th>Số điện thoại</th>
                                <th>CMND/CCCD</th>
                                <th>Địa chỉ</th>
                                <th>Tài khoản</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($khachHangs as $kh)
                                <tr>
                                    <td class="ps-3"><span class="badge bg-secondary">#{{ $kh->MaKhachHang }}</span></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-2" style="width:40px;height:40px;">
                                                <span class="fw-bold text-primary">{{ strtoupper(substr($kh->TenKhachHang, 0, 1)) }}</span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $kh->TenKhachHang }}</div>
                                                <small class="text-muted">{{ $kh->NgaySinh ? \Carbon\Carbon::parse($kh->NgaySinh)->format('d/m/Y') : '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $kh->SoDienThoai ?? 'N/A' }}</td>
                                    <td>{{ $kh->GiayChungMinh ?? 'N/A' }}</td>
                                    <td>{{ $kh->DiaChi ?? 'N/A' }}</td>
                                    <td>
                                        @if($kh->user)
                                            <span class="badge bg-info">{{ $kh->user->name }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.khach-hang.show', $kh->MaKhachHang) }}" class="btn btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.khach-hang.edit', $kh->MaKhachHang) }}" class="btn btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.khach-hang.destroy', $kh->MaKhachHang) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" title="Xóa"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                    <small class="text-muted">Hiển thị {{ $khachHangs->firstItem() }} - {{ $khachHangs->lastItem() }} trong tổng số {{ $khachHangs->total() }} khách hàng</small>
                    {{ $khachHangs->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có khách hàng nào</h5>
                    <a href="{{ route('admin.khach-hang.create') }}" class="btn btn-primary mt-2"><i class="fas fa-plus me-1"></i> Thêm khách hàng</a>
                </div>
            @endif
        </div>
    </div>
@endsection
