{{-- 
    ===================================================================
    TRANG ĐẶT PHÒNG - Form điền thông tin đặt phòng
    ===================================================================
    - Kế thừa layout nguoidung
    - Hiển thị thông tin phòng bên trái, form đặt phòng bên phải
    - Khách hàng phải điền đủ thông tin để đặt phòng
--}}
@extends('layout.nguoidung')

@section('title', 'Đặt phòng - ' . $phong->TenPhong)

@section('content')

{{-- Khoảng cách trên cùng cho navbar fixed --}}
<div style="padding-top:80px;" class="bg-light min-vh-100 pb-5">
    <div class="container">

        {{-- Tiêu đề trang --}}
        <div class="text-center mb-4 pt-4">
            <h2 class="fw-bold text-hotel">Đặt Phòng</h2>
            <p class="text-muted">Hoàn tất thông tin để đặt phòng của bạn</p>
        </div>

        {{-- Hiển thị thông báo lỗi nếu có (ví dụ: phòng đã được đặt) --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-4">

            {{-- ============================= --}}
            {{-- CỘT TRÁI: THÔNG TIN PHÒNG    --}}
            {{-- ============================= --}}
            <div class="col-lg-7">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    {{-- Minh họa phòng (CSS placeholder, không dùng ảnh ngoài) --}}
                    <div style="height:300px;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:10px;"
                         class="text-white"
                         style="background:linear-gradient(135deg, #667eea, #764ba2);">
                        @php
                            // Mảng màu gradient cho placeholder phòng
                            $roomColors = [
                                'linear-gradient(135deg, #667eea, #764ba2)',
                                'linear-gradient(135deg, #f093fb, #f5576c)',
                                'linear-gradient(135deg, #4facfe, #00f2fe)',
                            ];
                            $colorIndex = $phong->MaPhong % count($roomColors);
                        @endphp
                        <div style="height:300px;width:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:10px;background:{{ $roomColors[$colorIndex] }};"
                             class="text-white">
                            <i class="fas fa-bed" style="font-size:4rem;"></i>
                            <span class="fw-bold fs-5">{{ $phong->TenPhong }}</span>
                            @if($phong->loaiPhong)
                                <span class="badge bg-warning text-dark">{{ $phong->loaiPhong->TenLoaiPhong }}</span>
                            @endif
                        </div>
                    </div>
                    {{-- Nội dung thông tin phòng --}}
                    <div class="card-body p-4">
                        {{-- Badge loại phòng --}}
                        @if($phong->loaiPhong)
                            <span class="badge bg-warning text-dark mb-2">{{ $phong->loaiPhong->TenLoaiPhong }}</span>
                        @endif
                        {{-- Tên phòng --}}
                        <h3 class="fw-bold text-hotel">{{ $phong->TenPhong }}</h3>
                        {{-- Giá phòng --}}
                        <h4 class="fw-bold text-hotel">
                            {{ number_format($phong->GiaPhong, 0, ',', '.') }}₫ 
                            <small class="text-muted fw-normal">/ đêm</small>
                        </h4>
                        <hr>
                        {{-- Danh sách tiện nghi --}}
                        <div class="d-flex flex-wrap gap-3">
                            <span class="text-muted"><i class="fas fa-user text-gold me-1"></i> {{ $phong->SoNguoi }} Khách</span>
                            <span class="text-muted"><i class="fas fa-bed text-gold me-1"></i> {{ $phong->loaiPhong ? $phong->loaiPhong->TenLoaiPhong : 'Giường đơn' }}</span>
                            <span class="text-muted"><i class="fas fa-wifi text-gold me-1"></i> WiFi miễn phí</span>
                            <span class="text-muted"><i class="fas fa-snowflake text-gold me-1"></i> Điều hòa</span>
                            <span class="text-muted"><i class="fas fa-tv text-gold me-1"></i> TV màn hình phẳng</span>
                            <span class="text-muted"><i class="fas fa-coffee text-gold me-1"></i> Mini bar</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============================= --}}
            {{-- CỘT PHẢI: FORM ĐẶT PHÒNG    --}}
            {{-- ============================= --}}
            <div class="col-lg-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body p-4">
                        {{-- Tiêu đề form --}}
                        <h4 class="fw-bold text-hotel text-center mb-4">
                            <i class="fas fa-calendar-check text-gold me-2"></i> Thông tin đặt phòng
                        </h4>

                        {{-- Form đặt phòng - gửi POST đến route 'dat-phong.store' --}}
                        <form action="{{ route('dat-phong.store') }}" method="POST" id="bookingForm">
                            @csrf {{-- Token chống tấn công CSRF --}}
                            {{-- Mã phòng ẩn --}}
                            <input type="hidden" name="MaPhong" value="{{ $phong->MaPhong }}">

                            {{-- Họ tên khách hàng --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-user text-gold me-1"></i> Họ tên khách hàng <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="TenKhachHang" class="form-control @error('TenKhachHang') is-invalid @enderror"
                                       value="{{ old('TenKhachHang', $khachHang ? $khachHang->TenKhachHang : ($user->HoTen ?? ($user->name ?? ''))) }}"
                                       placeholder="Nhập họ tên khách hàng" required>
                                @error('TenKhachHang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Số điện thoại --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-phone text-gold me-1"></i> Số điện thoại <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="SoDienThoai" class="form-control @error('SoDienThoai') is-invalid @enderror"
                                       value="{{ old('SoDienThoai', $khachHang ? $khachHang->SoDienThoai : ($user?->SoDienThoai ?? '')) }}"
                                       placeholder="Nhập số điện thoại" required>
                                @error('SoDienThoai')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ngày sinh --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-birthday-cake text-gold me-1"></i> Ngày sinh <span class="text-danger">*</span>
                                </label>
                                <input type="date" name="NgaySinh" class="form-control @error('NgaySinh') is-invalid @enderror"
                                       value="{{ old('NgaySinh', $khachHang ? $khachHang->NgaySinh : ($user?->NgaySinh ?? '')) }}"
                                       required>
                                @error('NgaySinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Giấy chứng minh / CCCD --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-id-card text-gold me-1"></i> CMND / CCCD <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="GiayChungMinh" class="form-control @error('GiayChungMinh') is-invalid @enderror"
                                       value="{{ old('GiayChungMinh', $khachHang ? $khachHang->GiayChungMinh : ($user?->GiayChungMinh ?? '')) }}"
                                       placeholder="Nhập số CMND/CCCD" required>
                                @error('GiayChungMinh')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Địa chỉ --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-map-marker-alt text-gold me-1"></i> Địa chỉ <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="DiaChi" class="form-control @error('DiaChi') is-invalid @enderror"
                                       value="{{ old('DiaChi', $khachHang ? $khachHang->DiaChi : ($user?->DiaChi ?? '')) }}"
                                       placeholder="Nhập địa chỉ" required>
                                @error('DiaChi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>

                            {{-- Ngày nhận phòng --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-gold me-1"></i> Ngày nhận phòng
                                </label>
                                <input type="date" name="NgayDatPhong" class="form-control @error('NgayDatPhong') is-invalid @enderror"
                                       value="{{ old('NgayDatPhong', date('Y-m-d')) }}"
                                       min="{{ date('Y-m-d') }}" required id="ngayNhan">
                                @error('NgayDatPhong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Ngày trả phòng --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-alt text-gold me-1"></i> Ngày trả phòng
                                </label>
                                <input type="date" name="NgayTraPhong" class="form-control @error('NgayTraPhong') is-invalid @enderror"
                                       value="{{ old('NgayTraPhong', date('Y-m-d', strtotime('+1 day'))) }}"
                                       min="{{ date('Y-m-d', strtotime('+1 day')) }}" required id="ngayTra">
                                @error('NgayTraPhong')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Bảng tính tiền --}}
                            <div class="bg-light rounded-3 p-3 mb-3">
                                <div class="d-flex justify-content-between text-muted mb-2">
                                    <span>Giá phòng / đêm</span>
                                    <span>{{ number_format($phong->GiaPhong, 0, ',', '.') }}₫</span>
                                </div>
                                <div class="d-flex justify-content-between text-muted mb-2">
                                    <span>Số đêm</span>
                                    <span id="soDem">1</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between fw-bold text-hotel fs-5">
                                    <span>Tổng cộng</span>
                                    <span id="tongTien">{{ number_format($phong->GiaPhong, 0, ',', '.') }}₫</span>
                                </div>
                            </div>

                            {{-- Nút xác nhận đặt phòng --}}
                            <button type="submit" class="btn btn-hotel w-100 py-3 fw-bold">
                                <i class="fas fa-check-circle me-2"></i> Xác nhận đặt phòng
                            </button>
                        </form>

                        {{-- Ghi chú --}}
                        <div class="alert alert-warning mt-3 mb-0 small">
                            <i class="fas fa-info-circle me-1"></i>
                            <strong>Lưu ý:</strong> Đơn đặt phòng sẽ được gửi đến quản lý để duyệt.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // === Xử lý tính tiền tự động khi thay đổi ngày ===
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy các phần tử input và hiển thị
        var ngayNhan = document.getElementById('ngayNhan');
        var ngayTra = document.getElementById('ngayTra');
        var soDemEl = document.getElementById('soDem');
        var tongTienEl = document.getElementById('tongTien');
        var giaPhong = {{ $phong->GiaPhong }}; // Giá phòng từ server

        // Hàm tính tiền dựa trên số đêm
        function tinhTien() {
            var startDate = new Date(ngayNhan.value); // Ngày nhận phòng
            var endDate = new Date(ngayTra.value);     // Ngày trả phòng
            
            // Kiểm tra ngày hợp lệ và ngày trả > ngày nhận
            if (startDate && endDate && endDate > startDate) {
                var diffTime = endDate - startDate;                              // Chênh lệch thời gian (ms)
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));     // Đổi sang số ngày
                var tongTien = diffDays * giaPhong;                              // Tính tổng tiền
                
                soDemEl.textContent = diffDays;                                              // Hiển thị số đêm
                tongTienEl.textContent = new Intl.NumberFormat('vi-VN').format(tongTien) + '₫'; // Hiển thị tổng tiền
            }
        }

        // Khi thay đổi ngày nhận phòng
        ngayNhan.addEventListener('change', function() {
            // Tính ngày hôm sau để đặt min cho ngày trả
            var nextDay = new Date(this.value);
            nextDay.setDate(nextDay.getDate() + 1);
            ngayTra.min = nextDay.toISOString().split('T')[0];
            
            // Nếu ngày trả <= ngày nhận thì tự động đặt lại
            if (new Date(ngayTra.value) <= new Date(this.value)) {
                ngayTra.value = nextDay.toISOString().split('T')[0];
            }
            tinhTien(); // Tính lại tiền
        });

        // Khi thay đổi ngày trả phòng
        ngayTra.addEventListener('change', tinhTien);
        
        // Tính tiền lần đầu khi trang load xong
        tinhTien();
    });
</script>
@endpush
