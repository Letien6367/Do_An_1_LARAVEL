{{--
    ===================================================================
    TRANG CHI TIẾT HÓA ĐƠN
    ===================================================================
    - Hiển thị đầy đủ thông tin hóa đơn: mã HĐ, ngày lập, tổng tiền
    - Thông tin phòng: tên phòng, loại phòng, giá phòng
    - Thông tin khách hàng: tên, SĐT, CMND, địa chỉ
    - Thông tin đặt phòng: ngày đặt, ngày trả, số đêm, trạng thái
--}}

@extends('layout.quanly')

@section('title', 'Chi tiết hóa đơn #' . $hoaDon->MaHoaDon)

@section('content')

    {{-- ============================= --}}
    {{-- TIÊU ĐỀ + NÚT QUAY LẠI      --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1e3a5f;">
                <i class="fas fa-file-invoice me-2" style="color:#f0c14b;"></i>Hóa đơn #{{ $hoaDon->MaHoaDon }}
            </h2>
            <p class="text-muted mb-0">Chi tiết hóa đơn thanh toán</p>
        </div>
        <div class="d-flex gap-2 d-print-none">
            {{-- Nút in hóa đơn (mở hộp thoại in trình duyệt) --}}
            <button onclick="window.print()" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-print me-1"></i> In
            </button>
            {{-- Nút quay lại danh sách --}}
            <a href="{{ route('admin.hoa-don.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <div class="row g-4">

        {{-- ============================= --}}
        {{-- CỘT TRÁI: THÔNG TIN HÓA ĐƠN --}}
        {{-- ============================= --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3">
                {{-- Header card --}}
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
                        <i class="fas fa-info-circle me-2" style="color:#f0c14b;"></i>Thông tin hóa đơn
                    </h5>
                </div>
                <div class="card-body">

                    {{-- Bảng thông tin cơ bản hóa đơn --}}
                    <div class="row mb-4">
                        {{-- Cột: Mã hóa đơn --}}
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block mb-1">Mã hóa đơn</small>
                            <span class="fw-bold fs-5 text-primary">#{{ $hoaDon->MaHoaDon }}</span>
                        </div>
                        {{-- Cột: Ngày lập --}}
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block mb-1">Ngày lập hóa đơn</small>
                            <span class="fw-semibold">
                                <i class="fas fa-calendar-day text-muted me-1"></i>
                                {{ $hoaDon->NgayLapHD ? $hoaDon->NgayLapHD->format('d/m/Y') : 'N/A' }}
                            </span>
                        </div>
                        {{-- Cột: Tổng tiền --}}
                        <div class="col-md-4 mb-3">
                            <small class="text-muted d-block mb-1">Tổng tiền</small>
                            <span class="fw-bold fs-5 text-success">{{ number_format($hoaDon->TongTien, 0, ',', '.') }}đ</span>
                        </div>
                    </div>

                    <hr>

                    {{-- ============================= --}}
                    {{-- THÔNG TIN PHÒNG              --}}
                    {{-- ============================= --}}
                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;">
                        <i class="fas fa-bed me-2 text-primary"></i>Thông tin phòng
                    </h6>
                    @if($hoaDon->phong)
                        <div class="row mb-4">
                            {{-- Tên phòng --}}
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Tên phòng</small>
                                <span class="badge bg-light text-dark border fs-6">
                                    <i class="fas fa-door-open me-1 text-primary"></i>{{ $hoaDon->phong->TenPhong }}
                                </span>
                            </div>
                            {{-- Loại phòng --}}
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Loại phòng</small>
                                <span class="fw-semibold">{{ $hoaDon->phong->loaiPhong->TenLoaiPhong ?? 'N/A' }}</span>
                            </div>
                            {{-- Giá phòng / đêm --}}
                            <div class="col-md-4 mb-2">
                                <small class="text-muted d-block">Giá phòng / đêm</small>
                                <span class="fw-semibold text-warning">{{ number_format($hoaDon->phong->GiaPhong, 0, ',', '.') }}đ</span>
                            </div>
                        </div>
                    @else
                        <p class="text-muted">Không có thông tin phòng</p>
                    @endif

                    <hr>

                    {{-- ============================= --}}
                    {{-- THÔNG TIN ĐẶT PHÒNG          --}}
                    {{-- ============================= --}}
                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;">
                        <i class="fas fa-calendar-check me-2 text-success"></i>Thông tin đặt phòng
                    </h6>
                    @if($hoaDon->datPhong)
                        <div class="row mb-4">
                            {{-- Mã đặt phòng --}}
                            <div class="col-md-3 mb-2">
                                <small class="text-muted d-block">Mã đặt phòng</small>
                                <span class="fw-bold">#{{ $hoaDon->datPhong->MaDatPhong }}</span>
                            </div>
                            {{-- Ngày đặt --}}
                            <div class="col-md-3 mb-2">
                                <small class="text-muted d-block">Ngày đặt</small>
                                <span>{{ \Carbon\Carbon::parse($hoaDon->datPhong->NgayDatPhong)->format('d/m/Y') }}</span>
                            </div>
                            {{-- Ngày trả --}}
                            <div class="col-md-3 mb-2">
                                <small class="text-muted d-block">Ngày trả</small>
                                <span>{{ \Carbon\Carbon::parse($hoaDon->datPhong->NgayTraPhong)->format('d/m/Y') }}</span>
                            </div>
                            {{-- Số đêm --}}
                            <div class="col-md-3 mb-2">
                                <small class="text-muted d-block">Số đêm</small>
                                <span class="badge bg-info text-white">{{ $soNgay }} đêm</span>
                            </div>
                        </div>

                        {{-- Trạng thái đặt phòng --}}
                        <div class="mb-3">
                            <small class="text-muted d-block mb-1">Trạng thái đặt phòng</small>
                            @if($hoaDon->datPhong->trangThaiDatPhong)
                                @php
                                    $tt = $hoaDon->datPhong->MaTrangThaiDP;
                                    $badgeClass = match($tt) {
                                        1 => 'bg-secondary',
                                        2 => 'bg-primary',
                                        3 => 'bg-info text-dark',
                                        4 => 'bg-success',
                                        5 => 'bg-warning text-dark',
                                        6 => 'bg-dark',
                                        7 => 'bg-danger',
                                        default => 'bg-light text-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }} fs-6">{{ $hoaDon->datPhong->trangThaiDatPhong->TenTrangThaiDP }}</span>
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </div>
                    @else
                        <p class="text-muted">Không có thông tin đặt phòng</p>
                    @endif

                    <hr>

                    {{-- ============================= --}}
                    {{-- BẢNG TÍNH TIỀN CHI TIẾT       --}}
                    {{-- ============================= --}}
                    <h6 class="fw-bold mb-3" style="color:#1e3a5f;">
                        <i class="fas fa-calculator me-2 text-warning"></i>Chi tiết thanh toán
                    </h6>
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mô tả</th>
                                <th class="text-end">Đơn giá</th>
                                <th class="text-center">Số đêm</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Phòng {{ $hoaDon->phong->TenPhong ?? 'N/A' }}
                                    @if($hoaDon->phong && $hoaDon->phong->loaiPhong)
                                        <small class="text-muted">({{ $hoaDon->phong->loaiPhong->TenLoaiPhong }})</small>
                                    @endif
                                </td>
                                <td class="text-end">{{ $hoaDon->phong ? number_format($hoaDon->phong->GiaPhong, 0, ',', '.') . 'đ' : 'N/A' }}</td>
                                <td class="text-center">{{ $soNgay }}</td>
                                <td class="text-end fw-bold">{{ number_format($hoaDon->TongTien, 0, ',', '.') }}đ</td>
                            </tr>
                        </tbody>
                        {{-- Dòng tổng cộng --}}
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end fw-bold">TỔNG CỘNG:</td>
                                <td class="text-end fw-bold text-success fs-5">{{ number_format($hoaDon->TongTien, 0, ',', '.') }}đ</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        {{-- ============================= --}}
        {{-- CỘT PHẢI: THÔNG TIN KHÁCH HÀNG --}}
        {{-- ============================= --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white border-0 pt-3">
                    <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
                        <i class="fas fa-user me-2" style="color:#f0c14b;"></i>Khách hàng
                    </h5>
                </div>
                <div class="card-body text-center">
                    @if($hoaDon->datPhong && $hoaDon->datPhong->khachHang)
                        @php $kh = $hoaDon->datPhong->khachHang; @endphp

                        {{-- Avatar lớn: chữ cái đầu tên --}}
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold mx-auto mb-3"
                             style="width:70px;height:70px;background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:1.5rem;">
                            {{ strtoupper(mb_substr($kh->TenKhachHang, 0, 1)) }}
                        </div>

                        {{-- Tên khách hàng --}}
                        <h5 class="fw-bold mb-3">{{ $kh->TenKhachHang }}</h5>

                        {{-- Danh sách thông tin chi tiết --}}
                        <div class="text-start">
                            {{-- Số điện thoại --}}
                            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#f8f9fa;">
                                <i class="fas fa-phone text-primary"></i>
                                <div>
                                    <small class="text-muted d-block">Số điện thoại</small>
                                    <span class="fw-semibold">{{ $kh->SoDienThoai ?? 'Chưa cập nhật' }}</span>
                                </div>
                            </div>

                            {{-- CMND/CCCD --}}
                            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#f8f9fa;">
                                <i class="fas fa-id-card text-success"></i>
                                <div>
                                    <small class="text-muted d-block">CMND/CCCD</small>
                                    <span class="fw-semibold">{{ $kh->GiayChungMinh ?? 'Chưa cập nhật' }}</span>
                                </div>
                            </div>

                            {{-- Ngày sinh --}}
                            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#f8f9fa;">
                                <i class="fas fa-birthday-cake text-warning"></i>
                                <div>
                                    <small class="text-muted d-block">Ngày sinh</small>
                                    <span class="fw-semibold">{{ $kh->NgaySinh ? \Carbon\Carbon::parse($kh->NgaySinh)->format('d/m/Y') : 'Chưa cập nhật' }}</span>
                                </div>
                            </div>

                            {{-- Địa chỉ --}}
                            <div class="d-flex align-items-center gap-2 mb-2 p-2 rounded" style="background:#f8f9fa;">
                                <i class="fas fa-map-marker-alt text-danger"></i>
                                <div>
                                    <small class="text-muted d-block">Địa chỉ</small>
                                    <span class="fw-semibold">{{ $kh->DiaChi ?? 'Chưa cập nhật' }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- Trường hợp không có thông tin khách hàng --}}
                        <div class="text-muted py-3">
                            <i class="fas fa-user-slash fa-2x d-block mb-2"></i>
                            <p class="mb-0">Không có thông tin khách hàng</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ============================= --}}
            {{-- NÚT XÓA HÓA ĐƠN             --}}
            {{-- ============================= --}}
            <div class="card border-0 shadow-sm rounded-3 mt-3 d-print-none">
                <div class="card-body">
                    <form action="{{ route('admin.hoa-don.destroy', $hoaDon->MaHoaDon) }}" method="POST"
                          onsubmit="return confirm('Bạn có chắc muốn xóa hóa đơn #{{ $hoaDon->MaHoaDon }}?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i> Xóa hóa đơn này
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>

@endsection
