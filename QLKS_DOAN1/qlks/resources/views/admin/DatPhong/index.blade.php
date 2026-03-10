@extends('layout.quanly')

@section('title', 'Quản lý đặt phòng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý đặt phòng</h2>
            <p class="text-muted mb-0">Quản lý tất cả các đơn đặt phòng trong khách sạn</p>
        </div>
        <a href="{{ route('admin.dat-phong.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Đặt phòng mới
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
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3"><i class="fas fa-calendar-check fa-lg text-primary"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $tongDatPhong ?? 0 }}</h4><small class="text-muted">Tổng đặt phòng</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-warning border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-warning bg-opacity-10 p-3 me-3"><i class="fas fa-clock fa-lg text-warning"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $dangChoXacNhan ?? 0 }}</h4><small class="text-muted">Chờ xác nhận</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3"><i class="fas fa-check-circle fa-lg text-success"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $daXacNhan ?? 0 }}</h4><small class="text-muted">Đã xác nhận</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-start border-danger border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-danger bg-opacity-10 p-3 me-3"><i class="fas fa-times-circle fa-lg text-danger"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $daHuy ?? 0 }}</h4><small class="text-muted">Đã hủy</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.dat-phong.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Khách hàng, phòng..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Trạng thái</label>
                    <select name="trang_thai" class="form-select">
                        <option value="">Tất cả</option>
                        @foreach($trangThais as $tt)
                            <option value="{{ $tt->MaTrangThaiDP }}" {{ request('trang_thai') == $tt->MaTrangThaiDP ? 'selected' : '' }}>{{ $tt->TenTrangThaiDP }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Từ ngày</label>
                    <input type="date" name="tu_ngay" class="form-control" value="{{ request('tu_ngay') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small text-muted">Đến ngày</label>
                    <input type="date" name="den_ngay" class="form-control" value="{{ request('den_ngay') }}">
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Danh sách đặt phòng</h5>
        </div>
        <div class="card-body p-0">
            @if(isset($datPhongs) && $datPhongs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">STT</th>
                                <th>Phòng</th>
                                <th>Khách hàng</th>
                                <th>Ngày đặt / Ngày trả</th>
                                <th>Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datPhongs as $index => $datPhong)
                                <tr>
                                    <td class="ps-3">{{ $datPhongs->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-2"><i class="fas fa-bed text-primary"></i></div>
                                            <div>
                                                <div class="fw-semibold">{{ $datPhong->phong->TenPhong ?? 'N/A' }}</div>
                                                <small class="text-muted">{{ $datPhong->phong->loaiPhong->TenLoaiPhong ?? '' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="fw-semibold">{{ $datPhong->khachHang->TenKhachHang ?? 'N/A' }}</span></td>
                                    <td>
                                        <div>
                                            <span class="text-success"><i class="fas fa-sign-in-alt me-1"></i>{{ $datPhong->NgayDatPhong ? $datPhong->NgayDatPhong->format('d/m/Y') : 'N/A' }}</span><br>
                                            <span class="text-danger"><i class="fas fa-sign-out-alt me-1"></i>{{ $datPhong->NgayTraPhong ? $datPhong->NgayTraPhong->format('d/m/Y') : 'N/A' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = 'warning';
                                            $statusName = $datPhong->trangThaiDatPhong->TenTrangThaiDP ?? 'N/A';
                                            if (str_contains(strtolower($statusName), 'xác nhận')) $statusClass = 'success';
                                            if (str_contains(strtolower($statusName), 'hủy')) $statusClass = 'danger';
                                            if (str_contains(strtolower($statusName), 'hoàn thành') || str_contains(strtolower($statusName), 'trả')) $statusClass = 'info';
                                        @endphp
                                        <span class="badge bg-{{ $statusClass }}">{{ $statusName }}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.dat-phong.show', $datPhong->MaDatPhong) }}" class="btn btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.dat-phong.edit', $datPhong->MaDatPhong) }}" class="btn btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.dat-phong.destroy', $datPhong->MaDatPhong) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                    <small class="text-muted">Hiển thị {{ $datPhongs->firstItem() }} - {{ $datPhongs->lastItem() }} trong tổng số {{ $datPhongs->total() }} đặt phòng</small>
                    {{ $datPhongs->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có đặt phòng nào</h5>
                    <p class="text-muted">Hãy thêm đặt phòng mới để bắt đầu quản lý</p>
                    <a href="{{ route('admin.dat-phong.create') }}" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Đặt phòng mới</a>
                </div>
            @endif
        </div>
    </div>
@endsection
