{{--
    ===================================================================
    TRANG DANH SÁCH HÓA ĐƠN
    ===================================================================
    - Hiển thị thẻ thống kê: tổng hóa đơn, tổng doanh thu, hóa đơn tháng, doanh thu tháng
    - Bộ lọc: tìm kiếm, lọc theo ngày
    - Bảng danh sách hóa đơn kèm phân trang
--}}

@extends('layout.quanly')

@section('title', 'Quản lý hóa đơn')

@section('content')

    {{-- ============================= --}}
    {{-- TIÊU ĐỀ TRANG + NÚT TẠO MỚI --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1e3a5f;">
                <i class="fas fa-file-invoice me-2" style="color:#f0c14b;"></i>Quản lý hóa đơn
            </h2>
            <p class="text-muted mb-0">Quản lý tất cả hóa đơn thanh toán của khách sạn</p>
        </div>
        {{-- Nút tạo hóa đơn mới --}}
        <a href="{{ route('admin.hoa-don.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Tạo hóa đơn
        </a>
    </div>

    {{-- ============================= --}}
    {{-- THÔNG BÁO THÀNH CÔNG / LỖI   --}}
    {{-- ============================= --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ============================= --}}
    {{-- 2 THẺ THỐNG KÊ TỔNG QUAN     --}}
    {{-- ============================= --}}
    <div class="row g-3 mb-4">
        {{-- Thẻ 1: Tổng số hóa đơn --}}
        <div class="col-xl-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:55px;height:55px;background:#d5e3f5;">
                        <i class="fas fa-file-invoice fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-primary">{{ $tongHoaDon }}</h5>
                        <small class="text-muted">Tổng hóa đơn</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Thẻ 3: Hóa đơn tháng này --}}
        <div class="col-xl-6 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:55px;height:55px;background:#fde8c8;">
                        <i class="fas fa-calendar-alt fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-warning">{{ $hoaDonThangNay }}</h5>
                        <small class="text-muted">Hóa đơn tháng {{ \Carbon\Carbon::now()->month }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- BỘ LỌC TÌM KIẾM             --}}
    {{-- ============================= --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.hoa-don.index') }}" class="row g-2 align-items-end">
                {{-- Ô tìm kiếm theo tên phòng, khách hàng --}}
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">
                        <i class="fas fa-search me-1"></i>Tìm kiếm
                    </label>
                    <input type="text" name="search" class="form-control form-control-sm"
                           placeholder="Tên phòng, tên khách hàng..."
                           value="{{ request('search') }}">
                </div>

                {{-- Lọc từ ngày --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">
                        <i class="fas fa-calendar me-1"></i>Từ ngày
                    </label>
                    <input type="date" name="tu_ngay" class="form-control form-control-sm"
                           value="{{ request('tu_ngay') }}">
                </div>

                {{-- Lọc đến ngày --}}
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted">
                        <i class="fas fa-calendar me-1"></i>Đến ngày
                    </label>
                    <input type="date" name="den_ngay" class="form-control form-control-sm"
                           value="{{ request('den_ngay') }}">
                </div>

                {{-- Nút lọc + đặt lại --}}
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-sm btn-primary flex-fill">
                        <i class="fas fa-filter me-1"></i> Lọc
                    </button>
                    <a href="{{ route('admin.hoa-don.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================= --}}
    {{-- BẢNG DANH SÁCH HÓA ĐƠN       --}}
    {{-- ============================= --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white border-0 pt-3">
            <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
                <i class="fas fa-list-alt me-2" style="color:#f0c14b;"></i>Danh sách hóa đơn
                <span class="badge bg-primary ms-2">{{ $hoaDons->total() }}</span>
            </h5>
        </div>
        <div class="card-body pt-2">
            @if($hoaDons->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        {{-- Tiêu đề cột bảng --}}
                        <thead>
                            <tr class="text-white text-uppercase" style="background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:0.82rem;">
                                <th class="border-0 ps-3">Mã HĐ</th>
                                <th class="border-0">Phòng</th>
                                <th class="border-0">Khách hàng</th>
                                <th class="border-0">Ngày lập</th>
                                <th class="border-0">Tổng tiền</th>
                                <th class="border-0">Trạng thái ĐP</th>
                                <th class="border-0 text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Lặp qua từng hóa đơn --}}
                            @foreach($hoaDons as $hd)
                                <tr>
                                    {{-- Cột: Mã hóa đơn --}}
                                    <td class="ps-3"><strong>#{{ $hd->MaHoaDon }}</strong></td>

                                    {{-- Cột: Tên phòng --}}
                                    <td>
                                        @if($hd->phong)
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-door-open me-1 text-primary"></i>{{ $hd->phong->TenPhong }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Cột: Thông tin khách hàng (avatar + tên + SĐT) --}}
                                    <td>
                                        @if($hd->datPhong && $hd->datPhong->khachHang)
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Avatar: chữ cái đầu tên --}}
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                                     style="width:32px;height:32px;background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:0.8rem;">
                                                    {{ strtoupper(mb_substr($hd->datPhong->khachHang->TenKhachHang, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold" style="font-size:0.9rem;">{{ $hd->datPhong->khachHang->TenKhachHang }}</div>
                                                    <small class="text-muted">{{ $hd->datPhong->khachHang->SoDienThoai }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Không rõ</span>
                                        @endif
                                    </td>

                                    {{-- Cột: Ngày lập hóa đơn --}}
                                    <td>
                                        @if($hd->NgayLapHD)
                                            <i class="fas fa-calendar-day text-muted me-1"></i>
                                            {{ $hd->NgayLapHD->format('d/m/Y') }}
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    {{-- Cột: Tổng tiền (in đậm, màu xanh lá) --}}
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($hd->TongTien, 0, ',', '.') }}đ</span>
                                    </td>

                                    {{-- Cột: Trạng thái đặt phòng --}}
                                    <td>
                                        @if($hd->datPhong && $hd->datPhong->trangThaiDatPhong)
                                            @php
                                                // Chọn màu badge dựa trên mã trạng thái
                                                $tt = $hd->datPhong->MaTrangThaiDP;
                                                $badgeClass = match($tt) {
                                                    1 => 'bg-secondary',       // Chờ duyệt
                                                    2 => 'bg-primary',         // Đã duyệt
                                                    3 => 'bg-info text-dark',  // Chờ xác nhận
                                                    4 => 'bg-success',         // Đã xác nhận
                                                    5 => 'bg-warning text-dark', // Đang ở
                                                    6 => 'bg-dark',            // Đã trả phòng
                                                    7 => 'bg-danger',          // Đã hủy
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $hd->datPhong->trangThaiDatPhong->TenTrangThaiDP }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    {{-- Cột: Các nút thao tác --}}
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-1">
                                            {{-- Nút xem chi tiết --}}
                                            <a href="{{ route('admin.hoa-don.show', $hd->MaHoaDon) }}"
                                               class="btn btn-sm btn-outline-primary" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            {{-- Nút xóa hóa đơn --}}
                                            <form action="{{ route('admin.hoa-don.destroy', $hd->MaHoaDon) }}" method="POST"
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa hóa đơn #{{ $hd->MaHoaDon }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang --}}
                @if($hoaDons->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $hoaDons->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            @else
                {{-- Thông báo khi không có dữ liệu --}}
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-file-invoice fa-3x d-block mb-3"></i>
                    <p class="mb-1">Chưa có hóa đơn nào</p>
                    <a href="{{ route('admin.hoa-don.create') }}" class="btn btn-sm btn-primary mt-2">
                        <i class="fas fa-plus me-1"></i> Tạo hóa đơn đầu tiên
                    </a>
                </div>
            @endif
        </div>
    </div>

@endsection
