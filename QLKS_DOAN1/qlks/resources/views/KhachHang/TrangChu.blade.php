{{-- 
    ===================================================================
    TRANG CHỦ - Hiển thị cho khách hàng
    ===================================================================
    - Kế thừa layout nguoidung (layout chính cho người dùng)
    - Bao gồm: Hero banner, Form tìm phòng, Giới thiệu, Danh sách phòng, 
      Dịch vụ, Thống kê, Đánh giá, CTA (kêu gọi hành động)
--}}
@extends('layout.nguoidung')

@section('title', 'Trang chủ')

@push('styles')
<style>
    /* Hero banner - Dùng ảnh khách sạn làm nền + lớp phủ tối */
    .hero-section {
        background:
            linear-gradient(rgba(18, 39, 63, 0.62), rgba(18, 39, 63, 0.62)),
            url('{{ asset('layout/hotel-bg.jpg') }}') center/cover no-repeat;
        min-height: 100vh;
        display: flex;
        align-items: center;
    }
    .text-gold { color: #d4af37 !important; }
    .bg-hotel { background-color: #1e3a5f !important; }
    .btn-gold {
        background: linear-gradient(135deg, #d4af37, #c9a227);
        color: #1e3a5f; font-weight: 700; border: none;
    }
    .btn-gold:hover { background: linear-gradient(135deg, #c9a227, #b8931f); color: #1e3a5f; }
    /* Card phòng - hiệu ứng hover */
    .room-card:hover { transform: translateY(-5px); transition: 0.3s; }
    .room-card { transition: 0.3s; }
    /* Card dịch vụ */
    .service-icon-box {
        width: 70px; height: 70px;
        background: linear-gradient(135deg, #d4af37, #c9a227);
        border-radius: 50%;
    }
    /* Placeholder minh họa phòng (chỉ dùng CSS + icon) */
    .room-placeholder {
        height: 220px;
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 8px;
    }
    .room-placeholder i { font-size: 3rem; }
    .room-placeholder span { font-size: 0.85rem; font-weight: 600; }
    /* Placeholder giới thiệu */
    .about-placeholder {
        height: 350px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        flex-direction: column; gap: 10px;
    }
    .about-placeholder i { font-size: 4rem; }
    /* Avatar placeholder */
    .avatar-placeholder {
        width: 45px; height: 45px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 1.1rem;
    }
</style>
@endpush

@section('content')

{{-- ============================= --}}
{{-- HERO SECTION - Banner chính   --}}
{{-- ============================= --}}
<section class="hero-section text-white text-center">
    <div class="container">
        {{-- Dòng chữ phụ --}}
        <p class="text-uppercase text-gold fw-semibold letter-spacing-2 mb-3">
            <small>&#9733; Chào mừng đến với Luxury Hotel &#9733;</small>
        </p>
        {{-- Tiêu đề chính --}}
        <h1 class="display-3 fw-bold mb-3">
            Trải nghiệm <span class="text-gold">Đẳng Cấp</span><br>Khách Sạn 5 Sao
        </h1>
        {{-- Mô tả ngắn --}}
        <p class="lead text-white-50 mb-4 mx-auto" style="max-width:600px;">
            Nơi nghỉ dưỡng lý tưởng với dịch vụ hoàn hảo, không gian sang trọng và tiện nghi hiện đại bậc nhất
        </p>
        {{-- Nút hành động --}}
        <a href="#rooms" class="btn btn-gold btn-lg px-4">
            <i class="fas fa-bed me-2"></i> Xem Phòng
        </a>
    </div>
</section>



{{-- ============================= --}}
{{-- GIỚI THIỆU VỀ KHÁCH SẠN     --}}
{{-- ============================= --}}
<section id="about" class="py-5 bg-light">
    <div class="container py-4">
        <div class="row g-5 align-items-center">
            {{-- Cột minh họa (CSS placeholder, không dùng ảnh ngoài) --}}
            <div class="col-lg-6">
                <div class="position-relative">
                    <div class="about-placeholder shadow" style="background:linear-gradient(135deg,#e8e0d0,#f5f0e8);">
                        <i class="fas fa-hotel" style="color:#1e3a5f;"></i>
                        <span class="fw-bold" style="color:#1e3a5f; font-size:1.1rem;">LUXURY HOTEL</span>
                        <small class="text-muted">Không gian sang trọng & đẳng cấp</small>
                    </div>
                    {{-- Badge "15+ năm kinh nghiệm" --}}
                    <div class="position-absolute top-0 start-0 m-3 p-3 rounded-3 text-center shadow"
                         style="background:linear-gradient(135deg,#d4af37,#c9a227);color:#1e3a5f;">
                        <h3 class="fw-bold mb-0">15+</h3>
                        <small class="fw-semibold">Năm kinh nghiệm</small>
                    </div>
                </div>
            </div>
            {{-- Cột nội dung --}}
            <div class="col-lg-6">
                <p class="text-uppercase text-gold fw-semibold small mb-2">&#9733; Về chúng tôi</p>
                <h2 class="fw-bold text-hotel mb-3">Khách Sạn Sang Trọng Bậc Nhất Việt Nam</h2>
                <p class="text-muted mb-4">
                    Luxury Hotel tự hào là điểm đến lý tưởng cho những ai tìm kiếm sự hoàn hảo. 
                    Với hơn 15 năm kinh nghiệm, chúng tôi cam kết mang đến trải nghiệm nghỉ dưỡng đẳng cấp 5 sao.
                </p>
                {{-- Các tính năng nổi bật --}}
                <div class="row g-3 mb-4">
                    <div class="col-6 d-flex align-items-center gap-2">
                        <div class="bg-light border rounded-3 p-2 text-hotel"><i class="fas fa-concierge-bell"></i></div>
                        <span class="fw-semibold small">Dịch vụ 24/7</span>
                    </div>
                    <div class="col-6 d-flex align-items-center gap-2">
                        <div class="bg-light border rounded-3 p-2 text-hotel"><i class="fas fa-wifi"></i></div>
                        <span class="fw-semibold small">WiFi tốc độ cao</span>
                    </div>
                    <div class="col-6 d-flex align-items-center gap-2">
                        <div class="bg-light border rounded-3 p-2 text-hotel"><i class="fas fa-utensils"></i></div>
                        <span class="fw-semibold small">Nhà hàng cao cấp</span>
                    </div>
                    <div class="col-6 d-flex align-items-center gap-2">
                        <div class="bg-light border rounded-3 p-2 text-hotel"><i class="fas fa-spa"></i></div>
                        <span class="fw-semibold small">Spa & Wellness</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- DANH SÁCH PHÒNG              --}}
{{-- ============================= --}}
{{-- Hiển thị các phòng có sẵn, lấy từ biến $phongs được truyền từ TrangChuController --}}
<section id="rooms" class="py-5">
    <div class="container py-4">
        {{-- Tiêu đề phần --}}
        <div class="text-center mb-5">
            <p class="text-uppercase text-gold fw-semibold small">&#9733; Phòng nghỉ</p>
            <h2 class="fw-bold text-hotel">Các Loại Phòng Của Chúng Tôi</h2>
            <p class="text-muted">Lựa chọn không gian nghỉ dưỡng phù hợp với nhu cầu của bạn</p>
        </div>

        {{-- ========================================================= --}}
        {{-- BỘ LỌC TÌM KIẾM PHÒNG                                   --}}
        {{-- ========================================================= --}}
        {{-- 
            Form tìm kiếm gửi bằng GET để:
            1. Giữ lại giá trị đã chọn trên URL (để copy/share link được)
            2. Khi chọn xong thì cuộn xuống phần #rooms cho tiện
            
            3 tiêu chí lọc:
            - Loại phòng: VIP, Thường, Deluxe, ... (từ bảng loai_phong)
            - Trạng thái: Trống, Đã đặt, Đang sửa, ... (từ bảng trang_thai_phong)
            - Từ khóa: Tìm theo tên phòng (VD: "101", "Phòng VIP")
        --}}
        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body p-4">
                <form action="{{ url('/') }}" method="GET" id="formTimPhong">
                    <div class="row g-3 align-items-end">

                        {{-- Dropdown: Lọc theo Loại Phòng --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">
                                <i class="fas fa-layer-group me-1 text-gold"></i> Loại phòng
                            </label>
                            {{-- 
                                Duyệt qua $loaiPhongs để tạo danh sách option
                                request('loai_phong'): giữ lại giá trị đã chọn sau khi submit
                            --}}
                            <select name="loai_phong" class="form-select">
                                <option value="">-- Tất cả loại phòng --</option>
                                @foreach($loaiPhongs as $lp)
                                    <option value="{{ $lp->MaLoaiPhong }}" 
                                        {{ request('loai_phong') == $lp->MaLoaiPhong ? 'selected' : '' }}>
                                        {{ $lp->TenLoaiPhong }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Dropdown: Lọc theo Trạng Thái Phòng --}}
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">
                                <i class="fas fa-toggle-on me-1 text-gold"></i> Trạng thái
                            </label>
                            <select name="trang_thai" class="form-select">
                                <option value="">-- Tất cả trạng thái --</option>
                                @foreach($trangThaiPhongs as $tt)
                                    <option value="{{ $tt->MaTrangThai }}" 
                                        {{ request('trang_thai') == $tt->MaTrangThai ? 'selected' : '' }}>
                                        {{ $tt->TenTrangThai }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Ô nhập: Tìm theo tên phòng --}}
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small text-muted">
                                <i class="fas fa-search me-1 text-gold"></i> Tìm kiếm
                            </label>
                            <input type="text" name="tu_khoa" class="form-control" 
                                   value="{{ request('tu_khoa') }}" 
                                   placeholder="Nhập tên phòng (VD: 101, VIP...)">
                        </div>

                        {{-- Nút Tìm kiếm + Nút Xóa lọc --}}
                        <div class="col-md-2 d-flex gap-2">
                            {{-- Nút tìm kiếm --}}
                            <button type="submit" class="btn btn-gold w-100">
                                <i class="fas fa-search me-1"></i> Tìm
                            </button>
                            {{-- 
                                Nút xóa bộ lọc: chỉ hiển thị khi đang có lọc
                                Quay về trang chủ gốc (không có tham số GET) + cuộn đến #rooms
                            --}}
                            @if($dangTimKiem)
                                <a href="{{ url('/') }}#rooms" class="btn btn-outline-secondary" title="Xóa bộ lọc">
                                    <i class="fas fa-times"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- 
            Hiển thị thông báo kết quả tìm kiếm
            Chỉ hiện khi đang có bộ lọc hoạt động
        --}}
        @if($dangTimKiem)
            <div class="alert alert-info d-flex align-items-center mb-4 rounded-3" role="alert">
                <i class="fas fa-info-circle me-2 fs-5"></i>
                <div>
                    Tìm thấy <strong>{{ $phongs->count() }}</strong> phòng phù hợp
                    {{-- Hiển thị chi tiết bộ lọc đang áp dụng --}}
                    @if(request('loai_phong'))
                        — Loại: <strong>{{ $loaiPhongs->firstWhere('MaLoaiPhong', request('loai_phong'))->TenLoaiPhong ?? '' }}</strong>
                    @endif
                    @if(request('trang_thai'))
                        — Trạng thái: <strong>{{ $trangThaiPhongs->firstWhere('MaTrangThai', request('trang_thai'))->TenTrangThai ?? '' }}</strong>
                    @endif
                    @if(request('tu_khoa'))
                        — Từ khóa: "<strong>{{ request('tu_khoa') }}</strong>"
                    @endif
                </div>
            </div>
        @endif

        {{-- Lưới hiển thị phòng (3 cột trên desktop) --}}
        <div class="row g-4">
            {{-- Duyệt qua từng phòng, nếu không có phòng nào thì hiển thị thông báo trống --}}
            @forelse($phongs as $index => $phong)
                <div class="col-lg-4 col-md-6">
                    <div class="card border-0 shadow-sm room-card h-100 rounded-4 overflow-hidden">
                        {{-- Ảnh minh họa phòng--}}
                        <div class="position-relative">
                            @php
                                // Mảng màu gradient cho từng phòng (để phân biệt)
                                $roomColors = [
                                    'linear-gradient(135deg, #667eea, #764ba2)',
                                    'linear-gradient(135deg, #f093fb, #f5576c)',
                                    'linear-gradient(135deg, #4facfe, #00f2fe)',
                                    'linear-gradient(135deg, #43e97b, #38f9d7)',
                                    'linear-gradient(135deg, #fa709a, #fee140)',
                                    'linear-gradient(135deg, #a18cd1, #fbc2eb)',
                                ];
                                $colorIndex = $index % count($roomColors);
                            @endphp
                            <div class="room-placeholder text-white" style="background:{{ $roomColors[$colorIndex] }};">
                                <i class="fas fa-bed"></i>
                                <span>{{ $phong->TenPhong }}</span>
                            </div>
                            {{-- Badge trạng thái phòng (Trống / Đã đặt) --}}
                            @if($phong->trangThaiPhong)
                                <span class="badge position-absolute top-0 start-0 m-2 
                                    {{ strtolower($phong->trangThaiPhong->TenTrangThai) == 'trống' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $phong->trangThaiPhong->TenTrangThai }}
                                </span>
                            @endif
                        </div>
                        {{-- Nội dung card --}}
                        <div class="card-body">
                            {{-- Loại phòng --}}
                            <small class="text-gold fw-bold text-uppercase">
                                {{ $phong->loaiPhong ? $phong->loaiPhong->TenLoaiPhong : 'Phòng tiêu chuẩn' }}
                            </small>
                            {{-- Tên phòng --}}
                            <h5 class="fw-bold text-hotel mt-1">{{ $phong->TenPhong }}</h5>
                            {{-- Tiện nghi --}}
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                <span class="badge bg-light text-muted"><i class="fas fa-user me-1 text-gold"></i> {{ $phong->SoNguoi }} Khách</span>
                                <span class="badge bg-light text-muted"><i class="fas fa-wifi me-1 text-gold"></i> WiFi</span>
                                <span class="badge bg-light text-muted"><i class="fas fa-snowflake me-1 text-gold"></i> Điều hòa</span>
                            </div>
                        </div>
                        {{-- Footer card: Giá + Nút đặt --}}
                        <div class="card-footer bg-white border-top d-flex justify-content-between align-items-center">
                            <div>
                                <span class="fw-bold text-hotel fs-5">{{ number_format($phong->GiaPhong, 0, ',', '.') }}₫</span>
                                <small class="text-muted">/ đêm</small>
                            </div>
                            <a href="{{ route('dat-phong.create', $phong->MaPhong) }}" class="btn btn-sm btn-hotel">Đặt ngay</a>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Trường hợp không có phòng nào phù hợp --}}
                <div class="col-12 text-center py-5">
                    <i class="fas fa-bed text-muted" style="font-size:4rem;"></i>
                    @if($dangTimKiem)
                        {{-- Khi đang tìm kiếm mà không có kết quả --}}
                        <h5 class="text-muted mt-3">Không tìm thấy phòng phù hợp</h5>
                        <p class="text-muted">Hãy thử thay đổi bộ lọc hoặc từ khóa tìm kiếm</p>
                        <a href="{{ url('/') }}#rooms" class="btn btn-outline-secondary mt-2">
                            <i class="fas fa-undo me-1"></i> Xóa bộ lọc
                        </a>
                    @else
                        <h5 class="text-muted mt-3">Chưa có phòng nào</h5>
                        <p class="text-muted">Vui lòng quay lại sau</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- DỊCH VỤ CỦA KHÁCH SẠN       --}}
{{-- ============================= --}}
<section class="py-5 bg-hotel text-white">
    <div class="container py-4">
        <div class="text-center mb-5">
            <p class="text-uppercase text-gold fw-semibold small">&#9733; Dịch vụ</p>
            <h2 class="fw-bold">Tiện Ích Đẳng Cấp</h2>
            <p class="text-white-50">Dịch vụ cao cấp mang đến trải nghiệm hoàn hảo nhất</p>
        </div>
        <div class="row g-4">
            {{-- Dịch vụ 1 --}}
            <div class="col-lg-3 col-md-6">
                <div class="card bg-transparent border border-secondary text-white text-center p-4 h-100 rounded-4">
                    <div class="service-icon-box d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-swimmer fa-2x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Hồ bơi vô cực</h5>
                    <p class="text-white-50 small">View toàn cảnh thành phố tuyệt đẹp</p>
                </div>
            </div>
            {{-- Dịch vụ 2 --}}
            <div class="col-lg-3 col-md-6">
                <div class="card bg-transparent border border-secondary text-white text-center p-4 h-100 rounded-4">
                    <div class="service-icon-box d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-spa fa-2x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Spa & Massage</h5>
                    <p class="text-white-50 small">Liệu pháp thư giãn chuyên nghiệp</p>
                </div>
            </div>
            {{-- Dịch vụ 3 --}}
            <div class="col-lg-3 col-md-6">
                <div class="card bg-transparent border border-secondary text-white text-center p-4 h-100 rounded-4">
                    <div class="service-icon-box d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-dumbbell fa-2x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Phòng Gym</h5>
                    <p class="text-white-50 small">Thiết bị hiện đại, mở cửa 24/7</p>
                </div>
            </div>
            {{-- Dịch vụ 4 --}}
            <div class="col-lg-3 col-md-6">
                <div class="card bg-transparent border border-secondary text-white text-center p-4 h-100 rounded-4">
                    <div class="service-icon-box d-flex align-items-center justify-content-center mx-auto mb-3">
                        <i class="fas fa-utensils fa-2x text-dark"></i>
                    </div>
                    <h5 class="fw-bold">Nhà hàng 5 sao</h5>
                    <p class="text-white-50 small">Ẩm thực Á - Âu đa dạng</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ============================= --}}
{{-- THỐNG KÊ NỔI BẬT             --}}
{{-- ============================= --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-md-3 col-6">
                <h2 class="fw-bold text-hotel mb-0">200<span class="text-gold">+</span></h2>
                <p class="text-muted">Phòng cao cấp</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold text-hotel mb-0">50K<span class="text-gold">+</span></h2>
                <p class="text-muted">Khách hàng hài lòng</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold text-hotel mb-0">15<span class="text-gold">+</span></h2>
                <p class="text-muted">Năm kinh nghiệm</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold text-hotel mb-0">4.9<span class="text-gold">/5</span></h2>
                <p class="text-muted">Đánh giá trung bình</p>
            </div>
        </div>
    </div>
</section>



{{-- ============================= --}}
{{-- CTA - KÊU GỌI HÀNH ĐỘNG     --}}
{{-- ============================= --}}
<section class="py-5 text-white text-center" style="background:linear-gradient(135deg,#1e3a5f,#2d5a87);">
    <div class="container py-4">
        <h2 class="display-5 fw-bold mb-3">Sẵn Sàng Cho <span class="text-gold">Kỳ Nghỉ</span> Của Bạn?</h2>
        <p class="text-white-50 mb-4">Đặt phòng ngay hôm nay để nhận ưu đãi giảm giá 20% cho lần đặt đầu tiên!</p>
        <a href="#rooms" class="btn btn-gold btn-lg px-4">
            <i class="fas fa-calendar-check me-2"></i> Đặt Phòng Ngay
        </a>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // === Cuộn mượt đến phần tử khi click vào link anchor (#rooms, #about) ===
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            var target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    // === Xử lý form tìm kiếm phòng: thêm #rooms vào URL sau khi submit ===
    // Sau khi submit form (GET), trình duyệt sẽ cuộn tới phần phòng
    var formTimPhong = document.getElementById('formTimPhong');
    if (formTimPhong) {
        formTimPhong.addEventListener('submit', function() {
            // Thêm #rooms vào action URL để trang tự cuộn đến phần phòng sau khi tải
            this.action = this.action.split('#')[0] + '#rooms';
        });
    }
</script>
@endpush
