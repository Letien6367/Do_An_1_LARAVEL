{{--
    TRANG THỐNG KÊ DOANH THU
    -------------------------
    Gồm 3 phần chính:
    1. Thống kê tổng quan: 4 ô hiển thị (hôm nay, tháng, năm, tổng đặt phòng)
    2. Bộ lọc: cho phép lọc theo năm và tháng
    3. Bảng chi tiết: danh sách đặt phòng kèm số tiền
--}}

@extends('layout.quanly')

@section('title', 'Thống kê doanh thu')

@section('content')

    {{-- ==================== TIÊU ĐỀ TRANG ==================== --}}
    <div class="mb-4">
        <h2 class="fw-bold mb-1" style="color:#1e3a5f;">
            <i class="fas fa-chart-line me-2" style="color:#f0c14b;"></i>Thống kê doanh thu
        </h2>
        <p class="text-muted mb-0">Theo dõi doanh thu và phân tích hiệu quả kinh doanh khách sạn</p>
    </div>

    {{-- ==================== 4 Ô THỐNG KÊ TỔNG QUAN ==================== --}}
    {{-- Mỗi ô hiển thị: icon + số liệu + mô tả --}}
    <div class="row g-3 mb-4">

        {{-- Ô 1: Doanh thu hôm nay --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:55px;height:55px;background:#d5f5e3;">
                        <i class="fas fa-coins fa-lg text-success"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-success">{{ number_format($doanhThuHomNay, 0, ',', '.') }}đ</h5>
                        <small class="text-muted">Doanh thu hôm nay</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ô 2: Doanh thu tháng này --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:55px;height:55px;background:#d5e3f5;">
                        <i class="fas fa-calendar-alt fa-lg text-primary"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-primary">{{ number_format($doanhThuThangNay, 0, ',', '.') }}đ</h5>
                        <small class="text-muted">Tháng {{ now()->month }}/{{ now()->year }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ô 3: Doanh thu năm nay --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:55px;height:55px;background:#fde8c8;">
                        <i class="fas fa-chart-bar fa-lg text-warning"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-warning">{{ number_format($doanhThuNamNay, 0, ',', '.') }}đ</h5>
                        <small class="text-muted">Năm {{ now()->year }}</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ô 4: Tổng đặt phòng hợp lệ --}}
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100 rounded-3">
                <div class="card-body d-flex align-items-center gap-3">
                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                         style="width:55px;height:55px;background:#eac8fd;">
                        <i class="fas fa-file-invoice-dollar fa-lg" style="color:#8e44ad;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0" style="color:#8e44ad;">{{ $tongDatPhong }}</h5>
                        <small class="text-muted">Đặt phòng hợp lệ</small>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ==================== BỘ LỌC THEO NĂM / THÁNG ==================== --}}
    {{-- Gửi form GET về /admin/doanh-thu?nam=...&thang=... --}}
    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ url('/admin/doanh-thu') }}" class="d-flex align-items-center gap-3 flex-wrap">

                {{-- Nhãn --}}
                <label class="fw-bold mb-0" style="color:#1e3a5f;">
                    <i class="fas fa-filter me-1"></i> Lọc:
                </label>

                {{-- Dropdown chọn năm --}}
                <select name="nam" class="form-select form-select-sm" style="width:auto;">
                    @foreach($danhSachNam as $n)
                        <option value="{{ $n }}" {{ (int)$nam === (int)$n ? 'selected' : '' }}>
                            Năm {{ $n }}
                        </option>
                    @endforeach
                </select>

                {{-- Dropdown chọn tháng (để trống = tất cả) --}}
                <select name="thang" class="form-select form-select-sm" style="width:auto;">
                    <option value="">-- Tất cả tháng --</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ !is_null($thang) && (int)$thang === $i ? 'selected' : '' }}>
                            Tháng {{ $i }}
                        </option>
                    @endfor
                </select>

                {{-- Nút lọc --}}
                <button type="submit" class="btn btn-sm btn-primary">
                    <i class="fas fa-search me-1"></i> Lọc
                </button>

                {{-- Nút đặt lại (xóa bộ lọc) --}}
                <a href="{{ url('/admin/doanh-thu') }}" class="btn btn-sm btn-secondary text-decoration-none">
                    <i class="fas fa-redo me-1"></i> Đặt lại
                </a>
            </form>
        </div>
    </div>

    {{-- ==================== BẢNG CHI TIẾT ĐẶT PHÒNG ==================== --}}
    <div class="card border-0 shadow-sm rounded-3">

        {{-- Tiêu đề bảng + tổng doanh thu theo bộ lọc --}}
        <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center flex-wrap gap-2 pt-3">
            <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
                <i class="fas fa-list-alt me-2" style="color:#f0c14b;"></i>
                Chi tiết doanh thu - {{ $thang ? "Tháng {$thang}/{$nam}" : "Năm {$nam}" }}
            </h5>
            <span class="text-muted">
                Tổng: <strong class="text-success">{{ number_format($tongDoanhThuLoc, 0, ',', '.') }}đ</strong>
                ({{ $tongDatPhongLoc }} đặt phòng)
            </span>
        </div>

        <div class="card-body pt-0">

            {{-- Nếu có dữ liệu thì hiển thị bảng --}}
            @if($datPhongs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">

                        {{-- Hàng tiêu đề --}}
                        <thead>
                            <tr class="text-white text-uppercase" style="background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:0.85rem;">
                                <th class="border-0 ps-3">Mã ĐP</th>
                                <th class="border-0">Phòng</th>
                                <th class="border-0">Khách hàng</th>
                                <th class="border-0">Ngày đặt</th>
                                <th class="border-0">Ngày trả</th>
                                <th class="border-0">Số đêm</th>
                                <th class="border-0">Trạng thái</th>
                                <th class="border-0">Thành tiền</th>
                            </tr>
                        </thead>

                        {{-- Duyệt danh sách đặt phòng --}}
                        <tbody>
                            @foreach($datPhongs as $dp)
                                @php
                                    // Tính số đêm và thành tiền cho mỗi dòng
                                    $ngayDat   = \Carbon\Carbon::parse($dp->NgayDatPhong);
                                    $ngayTra   = \Carbon\Carbon::parse($dp->NgayTraPhong);
                                    $soNgay    = max(1, $ngayDat->diffInDays($ngayTra));
                                    $thanhTien = $dp->phong ? $dp->phong->GiaPhong * $soNgay : 0;
                                @endphp
                                <tr>
                                    {{-- Mã đặt phòng --}}
                                    <td class="ps-3"><strong>#{{ $dp->MaDatPhong }}</strong></td>

                                    {{-- Tên phòng + giá/đêm --}}
                                    <td>
                                        @if($dp->phong)
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-door-open me-1 text-primary"></i>{{ $dp->phong->TenPhong }}
                                            </span>
                                            <br><small class="text-muted">{{ number_format($dp->phong->GiaPhong, 0, ',', '.') }}đ/đêm</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Tên khách hàng + SĐT --}}
                                    <td>
                                        @if($dp->khachHang)
                                            <div class="d-flex align-items-center gap-2">
                                                {{-- Avatar tròn hiển thị chữ cái đầu --}}
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                     style="width:32px;height:32px;background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:0.8rem;">
                                                    {{ strtoupper(mb_substr($dp->khachHang->TenKhachHang, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold" style="font-size:0.9rem;">{{ $dp->khachHang->TenKhachHang }}</div>
                                                    <small class="text-muted">{{ $dp->khachHang->SoDienThoai }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Không rõ</span>
                                        @endif
                                    </td>

                                    {{-- Ngày đặt / Ngày trả / Số đêm --}}
                                    <td>{{ $ngayDat->format('d/m/Y') }}</td>
                                    <td>{{ $ngayTra->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">{{ $soNgay }} đêm</span>
                                    </td>

                                    {{-- Trạng thái đặt phòng (màu badge theo mã trạng thái) --}}
                                    <td>
                                        @if($dp->trangThaiDatPhong)
                                            @php
                                                // Gán màu badge theo mã trạng thái:
                                                // 2=Đã duyệt (xanh dương), 4=Đã xác nhận (xanh lá)
                                                // 5=Đang ở (vàng), 6=Đã trả phòng (xám)
                                                $badgeClass = match($dp->MaTrangThaiDP) {
                                                    2 => 'bg-primary',
                                                    4 => 'bg-success',
                                                    5 => 'bg-warning text-dark',
                                                    6 => 'bg-secondary',
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $dp->trangThaiDatPhong->TenTrangThaiDP }}</span>
                                        @endif
                                    </td>

                                    {{-- Thành tiền --}}
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($thanhTien, 0, ',', '.') }}đ</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Phân trang (giữ nguyên tham số lọc khi chuyển trang) --}}
                @if($datPhongs->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $datPhongs->appends(request()->query())->links('pagination::bootstrap-5') }}
                    </div>
                @endif

            {{-- Nếu không có dữ liệu --}}
            @else
                <div class="text-center py-5 text-muted">
                    <i class="fas fa-inbox fa-3x d-block mb-3"></i>
                    <p class="mb-0">Không có dữ liệu doanh thu trong khoảng thời gian này</p>
                </div>
            @endif

        </div>
    </div>

@endsection
