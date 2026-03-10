@extends('layout.quanly')

@section('title', 'Chi tiết phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.phong.index') }}">Phòng</a></li>
            <li class="breadcrumb-item active">{{ $phong->TenPhong }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-dark mb-0">Chi tiết phòng</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.phong.edit', $phong->MaPhong) }}" class="btn btn-warning"><i class="fas fa-edit me-1"></i> Sửa</a>
            <form action="{{ route('admin.phong.destroy', $phong->MaPhong) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Xóa</button>
            </form>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-body text-center py-4">
                    <div class="rounded-3 bg-primary bg-opacity-10 d-flex align-items-center justify-content-center mx-auto mb-3" style="width:70px;height:70px;">
                        <i class="fas fa-bed fa-2x text-primary"></i>
                    </div>
                    <h4 class="fw-bold">{{ $phong->TenPhong }}</h4>
                    <span class="badge bg-info mb-2">{{ $phong->loaiPhong->TenLoaiPhong ?? 'N/A' }}</span>
                    @php
                        $ttName = $phong->trangThaiPhong->TenTrangThai ?? 'N/A';
                        $ttClass = 'secondary';
                        if (str_contains(strtolower($ttName), 'trống') || str_contains(strtolower($ttName), 'sẵn')) $ttClass = 'success';
                        elseif (str_contains(strtolower($ttName), 'sử dụng') || str_contains(strtolower($ttName), 'thuê')) $ttClass = 'danger';
                        elseif (str_contains(strtolower($ttName), 'bảo trì') || str_contains(strtolower($ttName), 'sửa')) $ttClass = 'warning';
                    @endphp
                    <span class="badge bg-{{ $ttClass }}">{{ $ttName }}</span>

                    <div class="text-start mt-3">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-users me-1"></i> Sức chứa</small>
                            <span class="fw-semibold">{{ $phong->SoNguoi }} người</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-money-bill me-1"></i> Giá phòng</small>
                            <span class="fw-bold text-success">{{ number_format($phong->GiaPhong, 0, ',', '.') }} VNĐ</span>
                        </div>
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <small class="text-muted"><i class="fas fa-calendar me-1"></i> Ngày tạo</small>
                            <span class="fw-semibold">{{ $phong->created_at ? $phong->created_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <small class="text-muted"><i class="fas fa-sync me-1"></i> Cập nhật</small>
                            <span class="fw-semibold">{{ $phong->updated_at ? $phong->updated_at->format('d/m/Y') : 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="row g-2 mt-3">
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-2">
                                <h5 class="fw-bold text-primary mb-0">{{ $tongDatPhong ?? 0 }}</h5>
                                <small class="text-muted">Đặt phòng</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-3 p-2">
                                <h5 class="fw-bold text-success mb-0">{{ number_format($doanhThu ?? 0, 0, ',', '.') }}</h5>
                                <small class="text-muted">Doanh thu</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history text-primary me-2"></i>Lịch sử đặt phòng</h5>
                </div>
                <div class="card-body p-0">
                    @if($phong->datPhong && $phong->datPhong->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-3">Khách hàng</th>
                                        <th>Ngày đặt</th>
                                        <th>Ngày trả</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($phong->datPhong as $booking)
                                        <tr>
                                            <td class="ps-3 fw-semibold">{{ $booking->khachHang->TenKhachHang ?? 'N/A' }}</td>
                                            <td>{{ $booking->NgayDatPhong ? $booking->NgayDatPhong->format('d/m/Y') : 'N/A' }}</td>
                                            <td>{{ $booking->NgayTraPhong ? $booking->NgayTraPhong->format('d/m/Y') : 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $sName = $booking->trangThaiDatPhong->TenTrangThaiDP ?? 'N/A';
                                                    $sClass = 'secondary';
                                                    if (str_contains(strtolower($sName), 'xác nhận')) $sClass = 'success';
                                                    elseif (str_contains(strtolower($sName), 'hủy')) $sClass = 'danger';
                                                    elseif (str_contains(strtolower($sName), 'chờ')) $sClass = 'warning';
                                                @endphp
                                                <span class="badge bg-{{ $sClass }}">{{ $sName }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có lịch sử đặt phòng</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
