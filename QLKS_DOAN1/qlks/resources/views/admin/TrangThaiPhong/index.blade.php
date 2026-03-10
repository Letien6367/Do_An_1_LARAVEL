@extends('layout.quanly')

@section('title', 'Quản lý trạng thái phòng')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Quản lý trạng thái phòng</h2>
            <p class="text-muted mb-0">Quản lý các trạng thái phòng trong hệ thống</p>
        </div>
        <a href="{{ route('admin.trang-thai-phong.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm trạng thái
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
                    <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3"><i class="fas fa-toggle-on fa-lg text-primary"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $tongTrangThai ?? 0 }}</h4><small class="text-muted">Trạng thái</small></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6">
            <div class="card border-start border-success border-4 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-3 bg-success bg-opacity-10 p-3 me-3"><i class="fas fa-bed fa-lg text-success"></i></div>
                    <div><h4 class="fw-bold mb-0">{{ $tongPhong ?? 0 }}</h4><small class="text-muted">Tổng phòng</small></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.trang-thai-phong.index') }}" method="GET" class="row g-3 align-items-end">
                <div class="col-md-8">
                    <label class="form-label small text-muted">Tìm kiếm</label>
                    <input type="text" name="search" class="form-control" placeholder="Tên trạng thái..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Tìm kiếm</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Danh sách trạng thái phòng</h5>
        </div>
        <div class="card-body p-0">
            @if(isset($trangThaiPhongs) && $trangThaiPhongs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">STT</th>
                                <th>Tên trạng thái</th>
                                <th>Số phòng</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($trangThaiPhongs as $index => $tt)
                                <tr>
                                    <td class="ps-3">{{ $trangThaiPhongs->firstItem() + $index }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-3 bg-primary bg-opacity-10 p-2 me-2"><i class="fas fa-toggle-on text-primary"></i></div>
                                            <span class="fw-semibold">{{ $tt->TenTrangThai }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $tt->phong_count ?? 0 }} phòng</span></td>
                                    <td>{{ $tt->created_at ? $tt->created_at->format('d/m/Y') : 'N/A' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.trang-thai-phong.show', $tt->MaTrangThai) }}" class="btn btn-outline-info" title="Xem"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.trang-thai-phong.edit', $tt->MaTrangThai) }}" class="btn btn-outline-warning" title="Sửa"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.trang-thai-phong.destroy', $tt->MaTrangThai) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
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
                    <small class="text-muted">Hiển thị {{ $trangThaiPhongs->firstItem() }} - {{ $trangThaiPhongs->lastItem() }} trong tổng số {{ $trangThaiPhongs->total() }}</small>
                    {{ $trangThaiPhongs->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-toggle-on fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Chưa có trạng thái nào</h5>
                    <a href="{{ route('admin.trang-thai-phong.create') }}" class="btn btn-primary mt-2"><i class="fas fa-plus me-1"></i> Thêm trạng thái</a>
                </div>
            @endif
        </div>
    </div>
@endsection
