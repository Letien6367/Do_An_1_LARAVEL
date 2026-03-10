{{-- 
    ===================================================================
    TRANG ĐẶT PHÒNG THÀNH CÔNG
    ===================================================================
    - Hiển thị khi khách hàng đặt phòng thành công
    - Bao gồm: Icon thành công, chi tiết đơn đặt phòng, nút hành động
    - Dữ liệu: $datPhong (thông tin đơn đặt phòng)
--}}
@extends('layout.nguoidung')

@section('title', 'Đặt phòng thành công')

@section('content')

{{-- Khoảng cách trên cho navbar fixed --}}
<div style="padding-top:80px;" class="bg-light min-vh-100 pb-5">
    <div class="container" style="max-width:700px;">
        <div class="pt-4">

            {{-- Card thông báo thành công --}}
            <div class="card border-0 shadow-sm rounded-4 text-center p-5">

                {{-- Icon check màu xanh --}}
                <div class="mx-auto mb-4 d-flex align-items-center justify-content-center rounded-circle bg-success"
                     style="width:90px;height:90px;">
                    <i class="fas fa-check fa-3x text-white"></i>
                </div>

                {{-- Tiêu đề --}}
                <h2 class="fw-bold text-hotel mb-2">Đặt Phòng Thành Công!</h2>
                <p class="text-muted mb-4">
                    Cảm ơn bạn đã đặt phòng tại Luxury Hotel.<br>
                    Đơn đặt phòng của bạn đang được xử lý và sẽ được duyệt sớm nhất.
                </p>

                {{-- Chi tiết đơn đặt phòng --}}
                <div class="bg-light rounded-3 p-4 text-start mb-4">
                    <h5 class="fw-bold text-hotel border-bottom pb-3 mb-3">
                        <i class="fas fa-receipt text-gold me-2"></i> Chi tiết đặt phòng
                    </h5>

                    {{-- Mã đặt phòng --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-hashtag text-gold me-2"></i> Mã đặt phòng</span>
                        <span class="fw-bold text-hotel">#{{ $datPhong->MaDatPhong }}</span>
                    </div>

                    {{-- Tên phòng --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-door-open text-gold me-2"></i> Phòng</span>
                        <span class="fw-bold text-hotel">{{ $datPhong->phong->TenPhong }}</span>
                    </div>

                    {{-- Loại phòng --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-bed text-gold me-2"></i> Loại phòng</span>
                        <span class="fw-bold text-hotel">{{ $datPhong->phong->loaiPhong ? $datPhong->phong->loaiPhong->TenLoaiPhong : 'N/A' }}</span>
                    </div>

                    {{-- Ngày nhận phòng --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-calendar-alt text-gold me-2"></i> Ngày nhận phòng</span>
                        <span class="fw-bold text-hotel">{{ \Carbon\Carbon::parse($datPhong->NgayDatPhong)->format('d/m/Y') }}</span>
                    </div>

                    {{-- Ngày trả phòng --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-calendar-alt text-gold me-2"></i> Ngày trả phòng</span>
                        <span class="fw-bold text-hotel">{{ \Carbon\Carbon::parse($datPhong->NgayTraPhong)->format('d/m/Y') }}</span>
                    </div>

                    {{-- Tính số đêm và tổng tiền --}}
                    @php
                        // Tính số đêm = ngày trả - ngày nhận
                        $soDem = \Carbon\Carbon::parse($datPhong->NgayDatPhong)->diffInDays(\Carbon\Carbon::parse($datPhong->NgayTraPhong));
                        // Tổng tiền = số đêm * giá phòng
                        $tongTien = $soDem * $datPhong->phong->GiaPhong;
                    @endphp

                    {{-- Số đêm --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-moon text-gold me-2"></i> Số đêm</span>
                        <span class="fw-bold text-hotel">{{ $soDem }} đêm</span>
                    </div>

                    {{-- Tổng tiền --}}
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted"><i class="fas fa-money-bill-wave text-gold me-2"></i> Tổng tiền</span>
                        <span class="fw-bold text-gold fs-5">{{ number_format($tongTien, 0, ',', '.') }}₫</span>
                    </div>

                    {{-- Trạng thái --}}
                    <div class="d-flex justify-content-between py-2">
                        <span class="text-muted"><i class="fas fa-info-circle text-gold me-2"></i> Trạng thái</span>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-clock me-1"></i> {{ $datPhong->trangThaiDatPhong->TenTrangThaiDP }}
                        </span>
                    </div>
                </div>

                {{-- Nút hành động --}}
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="{{ route('trangchu') }}" class="btn btn-outline-secondary px-4">
                        <i class="fas fa-home me-1"></i> Về trang chủ
                    </a>
                    @auth
                    <a href="{{ route('dat-phong.lich-su') }}" class="btn btn-hotel px-4">
                        <i class="fas fa-history me-1"></i> Xem lịch sử đặt phòng
                    </a>
                    @endauth
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
