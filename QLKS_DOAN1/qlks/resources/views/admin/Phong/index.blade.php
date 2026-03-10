@extends('layout.quanly')

@section('title', 'Quản lý phòng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý phòng</h2>
            <p class="text-muted mb-0">Quản lý danh sách phòng trong khách sạn</p>
        </div>
        <a href="{{ route('admin.phong.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm phòng
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i>{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-primary border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3"><i class="fas fa-bed fa-lg text-primary"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $tongPhong ?? 0 }}</h4><small class="text-muted">Tổng phòng</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3"><i class="fas fa-check-circle fa-lg text-success"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $phongTrong ?? 0 }}</h4><small class="text-muted">Phòng trống</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-danger border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-danger bg-opacity-10 p-3 me-3"><i class="fas fa-door-closed fa-lg text-danger"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $phongDangSuDung ?? 0 }}</h4><small class="text-muted">Đang sử dụng</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3"><i class="fas fa-tools fa-lg text-warning"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $phongBaoTri ?? 0 }}</h4><small class="text-muted">Bảo trì</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.phong.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small text-muted">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên phòng..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Loại phòng</label>
                    <select name="loai_phong" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($loaiPhongs as $lp)
                            <option value="{{ $lp->MaLoaiPhong }}" {{ request('loai_phong') == $lp->MaLoaiPhong ? 'selected' : '' }}>{{ $lp->TenLoaiPhong }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($trangThais as $tt)
                            <option value="{{ $tt->MaTrangThai }}" {{ request('trang_thai') == $tt->MaTrangThai ? 'selected' : '' }}>{{ $tt->TenTrangThai }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Danh sách phòng</h5>
        </div>
        <div class="card-body p-0">
            @if(isset($phongs) && $phongs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">STT</th>
                                <th>Tên phòng</th>
                                <th>Loại phòng</th>
                                <th>Số người</th>
                                <th>Giá phòng</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($phongs as $index => $phong)
                                <tr>
                                    <td class="ps-3">{{ $phongs->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-2"><i class="fas fa-bed text-primary"></i></div>
                                            <span class="fw-semibold">{{ $phong->TenPhong }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-info">{{ $phong->loaiPhong->TenLoaiPhong ?? 'N/A' }}</span></td>
                                    <td><i class="fas fa-users text-muted me-1"></i>{{ $phong->SoNguoi }}</td>
                                    <td class="text-success fw-semibold">{{ number_format($phong->GiaPhong, 0, ',', '.') }} VNĐ</td>
                                    <td>
                                        @php
                                            $ttName = $phong->trangThaiPhong->TenTrangThai ?? 'N/A';
                                            $ttClass = 'secondary';
                                            if (str_contains(strtolower($ttName), 'trống') || str_contains(strtolower($ttName), 'sẵn')) $ttClass = 'success';
                                            elseif (str_contains(strtolower($ttName), 'sử dụng') || str_contains(strtolower($ttName), 'thuê')) $ttClass = 'danger';
                                            elseif (str_contains(strtolower($ttName), 'bảo trì') || str_contains(strtolower($ttName), 'sửa')) $ttClass = 'warning';
                                        @endphp
                                        <span class="badge bg-{{ $ttClass }}">{{ $ttName }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.phong.show', $phong->MaPhong) }}" class="btn btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.phong.edit', $phong->MaPhong) }}" class="btn btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.phong.destroy', $phong->MaPhong) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                    <small class="text-muted">Hiển thị {{ $phongs->firstItem() }} - {{ $phongs->lastItem() }} trong tổng số {{ $phongs->total() }} phòng</small>
                    {{ $phongs->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-bed fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có phòng nào</h5>
                    <a href="{{ route('admin.phong.create') }}" class="btn btn-primary mt-2"><i class="fas fa-plus me-1"></i> Thêm phòng</a>
                </div>
            @endif
        </div>
    </div>
@endsection
