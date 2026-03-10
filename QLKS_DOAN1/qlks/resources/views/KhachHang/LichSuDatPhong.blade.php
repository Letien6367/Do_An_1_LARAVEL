{{-- 
    ===================================================================
    TRANG LỊCH SỬ ĐẶT PHÒNG
    ===================================================================
    - Hiển thị danh sách các đơn đặt phòng của khách hàng đang đăng nhập
    - Cho phép hủy đặt phòng (nếu trạng thái cho phép)
    - Dữ liệu: $datPhongs (danh sách đơn đặt phòng, có phân trang)
--}}
@extends('layout.nguoidung')

@section('title', 'Lịch sử đặt phòng')

@section('content')

{{-- Khoảng cách trên cho navbar fixed --}}
<div style="padding-top:80px;" class="bg-light min-vh-100 pb-5">
    <div class="container" style="max-width:1000px;">

        {{-- Tiêu đề trang --}}
        <div class="text-center mb-4 pt-4">
            <h2 class="fw-bold text-hotel">Lịch Sử Đặt Phòng</h2>
            <p class="text-muted">Quản lý và theo dõi các đơn đặt phòng của bạn</p>
        </div>

        {{-- Hiển thị thông báo thành công (ví dụ: hủy phòng thành công) --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Hiển thị thông báo lỗi --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Kiểm tra có đơn đặt phòng nào không --}}
        @if(count($datPhongs) > 0)

            {{-- Duyệt qua từng đơn đặt phòng --}}
            @foreach($datPhongs as $datPhong)
                @php
                    // Mảng màu gradient cho placeholder phòng
                    $roomColors = [
                        'linear-gradient(135deg, #667eea, #764ba2)',
                        'linear-gradient(135deg, #f093fb, #f5576c)',
                        'linear-gradient(135deg, #4facfe, #00f2fe)',
                    ];
                    $colorIndex = $datPhong->MaPhong % count($roomColors);

                    // Tính số đêm = chênh lệch ngày trả - ngày nhận
                    $soDem = \Carbon\Carbon::parse($datPhong->NgayDatPhong)->diffInDays(\Carbon\Carbon::parse($datPhong->NgayTraPhong));
                    // Tính tổng tiền = số đêm * giá phòng
                    $tongTien = $datPhong->phong ? $soDem * $datPhong->phong->GiaPhong : 0;

                    // Xác định màu badge cho trạng thái đơn đặt phòng
                    $trangThai = strtolower($datPhong->trangThaiDatPhong->TenTrangThaiDP ?? '');
                    $badgeClass = 'bg-warning text-dark'; // Mặc định: Chờ duyệt (vàng)
                    $badgeIcon = 'fas fa-clock';

                    if (str_contains($trangThai, 'duyệt') && !str_contains($trangThai, 'chờ')) {
                        $badgeClass = 'bg-info text-white';    // Đã duyệt (xanh dương)
                        $badgeIcon = 'fas fa-check';
                    } elseif (str_contains($trangThai, 'đang ở')) {
                        $badgeClass = 'bg-success text-white'; // Đang ở (xanh lá)
                        $badgeIcon = 'fas fa-door-open';
                    } elseif (str_contains($trangThai, 'trả')) {
                        $badgeClass = 'bg-secondary text-white'; // Đã trả phòng (xám)
                        $badgeIcon = 'fas fa-check-double';
                    } elseif (str_contains($trangThai, 'hủy')) {
                        $badgeClass = 'bg-danger text-white';  // Đã hủy (đỏ)
                        $badgeIcon = 'fas fa-times';
                    }
                @endphp

                {{-- Card đơn đặt phòng --}}
                <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden">
                    {{-- Header card: mã đặt phòng + ngày tạo --}}
                    <div class="card-header bg-hotel text-white d-flex justify-content-between align-items-center py-3">
                        <span class="fw-bold">
                            <i class="fas fa-receipt me-1"></i> Mã đặt phòng: #{{ $datPhong->MaDatPhong }}
                        </span>
                        <small class="text-white-50">
                            <i class="fas fa-clock me-1"></i> {{ $datPhong->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>

                    {{-- Body card: chi tiết đơn --}}
                    <div class="card-body p-3">
                        <div class="row g-3 align-items-center">
                            {{-- Minh họa phòng (CSS placeholder) --}}
                            <div class="col-md-2 text-center">
                                <div class="rounded-3 d-flex align-items-center justify-content-center text-white"
                                     style="width:100px;height:75px;background:{{ $roomColors[$colorIndex] }};margin:0 auto;">
                                    <i class="fas fa-bed fa-2x"></i>
                                </div>
                            </div>

                            {{-- Thông tin đơn --}}
                            <div class="col-md-6">
                                <h6 class="fw-bold text-hotel mb-1">{{ $datPhong->phong->TenPhong ?? 'N/A' }}</h6>
                                <div class="d-flex flex-wrap gap-3 text-muted small">
                                    <span><i class="fas fa-bed text-gold me-1"></i> {{ $datPhong->phong->loaiPhong->TenLoaiPhong ?? 'N/A' }}</span>
                                    <span><i class="fas fa-calendar-check text-gold me-1"></i> {{ \Carbon\Carbon::parse($datPhong->NgayDatPhong)->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-calendar-times text-gold me-1"></i> {{ \Carbon\Carbon::parse($datPhong->NgayTraPhong)->format('d/m/Y') }}</span>
                                    <span><i class="fas fa-moon text-gold me-1"></i> {{ $soDem }} đêm</span>
                                </div>
                                {{-- Tổng tiền --}}
                                <div class="fw-bold text-hotel fs-5 mt-1">
                                    {{ number_format($tongTien, 0, ',', '.') }}₫
                                </div>
                            </div>

                            {{-- Trạng thái + nút hủy --}}
                            <div class="col-md-4 text-md-end">
                                {{-- Badge trạng thái --}}
                                <span class="badge {{ $badgeClass }} px-3 py-2 mb-2">
                                    <i class="{{ $badgeIcon }} me-1"></i>
                                    {{ $datPhong->trangThaiDatPhong->TenTrangThaiDP ?? 'N/A' }}
                                </span>
                                <br>
                                {{-- Nút hủy: chỉ hiện khi trạng thái là "Chờ duyệt" (1) hoặc "Đã duyệt" (2) --}}
                                @if(in_array($datPhong->MaTrangThaiDP, [1, 2]))
                                    <form action="{{ route('dat-phong.huy', $datPhong->MaDatPhong) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn đặt phòng này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm mt-1">
                                            <i class="fas fa-times me-1"></i> Hủy đặt phòng
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Phân trang (Bootstrap pagination) --}}
            <div class="d-flex justify-content-center mt-4">
                {{ $datPhongs->links() }}
            </div>

        @else
            {{-- Trường hợp chưa có đơn đặt phòng nào --}}
            <div class="card border-0 shadow-sm rounded-4 text-center p-5">
                <i class="fas fa-calendar-times text-muted" style="font-size:4rem;"></i>
                <h5 class="text-muted mt-3">Chưa có đơn đặt phòng nào</h5>
                <p class="text-muted">Bạn chưa đặt phòng nào. Hãy khám phá các phòng của chúng tôi!</p>
                <a href="{{ route('trangchu') }}#rooms" class="btn btn-hotel px-4">
                    <i class="fas fa-bed me-1"></i> Đặt phòng ngay
                </a>
            </div>
        @endif

    </div>
</div>

@endsection
