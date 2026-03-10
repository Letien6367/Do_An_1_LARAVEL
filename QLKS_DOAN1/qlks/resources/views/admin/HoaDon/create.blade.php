{{--
    ===================================================================
    TRANG TẠO HÓA ĐƠN MỚI
    ===================================================================
    - Hiển thị danh sách đặt phòng chưa có hóa đơn
    - Admin chọn 1 đặt phòng → hệ thống tự tính tiền
    - Tổng tiền = Giá phòng × Số đêm
--}}

@extends('layout.quanly')

@section('title', 'Tạo hóa đơn mới')

@section('content')

    {{-- ============================= --}}
    {{-- TIÊU ĐỀ TRANG + NÚT QUAY LẠI --}}
    {{-- ============================= --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="fw-bold mb-1" style="color:#1e3a5f;">
                <i class="fas fa-plus-circle me-2" style="color:#f0c14b;"></i>Tạo hóa đơn mới
            </h2>
            <p class="text-muted mb-0">Chọn đặt phòng để tạo hóa đơn thanh toán</p>
        </div>
        {{-- Nút quay lại danh sách --}}
        <a href="{{ route('admin.hoa-don.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    {{-- ============================= --}}
    {{-- THÔNG BÁO LỖI                --}}
    {{-- ============================= --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Hiển thị lỗi validate --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ============================= --}}
    {{-- KIỂM TRA: CÒN ĐẶT PHÒNG NÀO CHƯA CÓ HÓA ĐƠN KHÔNG --}}
    {{-- ============================= --}}
    @if($datPhongs->count() > 0)

        {{-- ============================= --}}
        {{-- DANH SÁCH ĐẶT PHÒNG CÓ THỂ TẠO HÓA ĐƠN --}}
        {{-- ============================= --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-white border-0 pt-3">
                <h5 class="fw-bold mb-0" style="color:#1e3a5f;">
                    <i class="fas fa-bed me-2" style="color:#f0c14b;"></i>Đặt phòng chưa có hóa đơn
                    <span class="badge bg-warning text-dark ms-2">{{ $datPhongs->count() }}</span>
                </h5>
                <small class="text-muted">Chọn một đặt phòng bên dưới để tạo hóa đơn</small>
            </div>
            <div class="card-body pt-2">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        {{-- Tiêu đề bảng --}}
                        <thead>
                            <tr class="text-white text-uppercase" style="background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:0.82rem;">
                                <th class="border-0 ps-3">Mã ĐP</th>
                                <th class="border-0">Phòng</th>
                                <th class="border-0">Khách hàng</th>
                                <th class="border-0">Ngày đặt</th>
                                <th class="border-0">Ngày trả</th>
                                <th class="border-0">Số đêm</th>
                                <th class="border-0">Giá phòng</th>
                                <th class="border-0">Thành tiền</th>
                                <th class="border-0">Trạng thái</th>
                                <th class="border-0 text-center">Tạo HĐ</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Lặp qua từng đặt phòng chưa có hóa đơn --}}
                            @foreach($datPhongs as $dp)
                                @php
                                    // Tính số đêm giữa ngày đặt và ngày trả (tối thiểu 1 đêm)
                                    $ngayDat = \Carbon\Carbon::parse($dp->NgayDatPhong);
                                    $ngayTra = \Carbon\Carbon::parse($dp->NgayTraPhong);
                                    $soNgay = max(1, $ngayDat->diffInDays($ngayTra));

                                    // Tính thành tiền = giá phòng × số đêm
                                    $giaPhong = $dp->phong ? $dp->phong->GiaPhong : 0;
                                    $thanhTien = $giaPhong * $soNgay;
                                @endphp
                                <tr>
                                    {{-- Mã đặt phòng --}}
                                    <td class="ps-3"><strong>#{{ $dp->MaDatPhong }}</strong></td>

                                    {{-- Tên phòng --}}
                                    <td>
                                        @if($dp->phong)
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-door-open me-1 text-primary"></i>{{ $dp->phong->TenPhong }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>

                                    {{-- Khách hàng --}}
                                    <td>
                                        @if($dp->khachHang)
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold flex-shrink-0"
                                                     style="width:30px;height:30px;background:linear-gradient(135deg,#1e3a5f,#2d5a87);font-size:0.75rem;">
                                                    {{ strtoupper(mb_substr($dp->khachHang->TenKhachHang, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold" style="font-size:0.85rem;">{{ $dp->khachHang->TenKhachHang }}</div>
                                                    <small class="text-muted">{{ $dp->khachHang->SoDienThoai }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Không rõ</span>
                                        @endif
                                    </td>

                                    {{-- Ngày đặt phòng --}}
                                    <td><small>{{ $ngayDat->format('d/m/Y') }}</small></td>

                                    {{-- Ngày trả phòng --}}
                                    <td><small>{{ $ngayTra->format('d/m/Y') }}</small></td>

                                    {{-- Số đêm --}}
                                    <td class="text-center">
                                        <span class="badge bg-info text-white">{{ $soNgay }} đêm</span>
                                    </td>

                                    {{-- Giá phòng mỗi đêm --}}
                                    <td><small class="text-muted">{{ number_format($giaPhong, 0, ',', '.') }}đ</small></td>

                                    {{-- Thành tiền (in đậm, xanh lá) --}}
                                    <td>
                                        <span class="fw-bold text-success">{{ number_format($thanhTien, 0, ',', '.') }}đ</span>
                                    </td>

                                    {{-- Trạng thái đặt phòng --}}
                                    <td>
                                        @if($dp->trangThaiDatPhong)
                                            @php
                                                $tt = $dp->MaTrangThaiDP;
                                                $badgeClass = match($tt) {
                                                    2 => 'bg-primary',
                                                    4 => 'bg-success',
                                                    5 => 'bg-warning text-dark',
                                                    6 => 'bg-dark',
                                                    default => 'bg-light text-dark'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $dp->trangThaiDatPhong->TenTrangThaiDP }}</span>
                                        @endif
                                    </td>

                                    {{-- Nút tạo hóa đơn --}}
                                    <td class="text-center">
                                        <form action="{{ route('admin.hoa-don.store') }}" method="POST"
                                              onsubmit="return confirm('Xác nhận tạo hóa đơn cho đặt phòng #{{ $dp->MaDatPhong }}?\nTổng tiền: {{ number_format($thanhTien, 0, ',', '.') }}đ')">
                                            @csrf
                                            {{-- Gửi mã đặt phòng lên server --}}
                                            <input type="hidden" name="MaDatPhong" value="{{ $dp->MaDatPhong }}">
                                            <button type="submit" class="btn btn-sm btn-success" title="Tạo hóa đơn">
                                                <i class="fas fa-file-invoice-dollar me-1"></i> Tạo
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    @else
        {{-- ============================= --}}
        {{-- KHÔNG CÒN ĐẶT PHÒNG NÀO ĐỂ TẠO HÓA ĐƠN --}}
        {{-- ============================= --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-body text-center py-5 text-muted">
                <i class="fas fa-check-circle fa-3x d-block mb-3 text-success"></i>
                <h5 class="fw-bold">Tất cả đặt phòng đã có hóa đơn!</h5>
                <p class="mb-3">Hiện không có đặt phòng nào cần tạo hóa đơn.</p>
                <a href="{{ route('admin.hoa-don.index') }}" class="btn btn-primary">
                    <i class="fas fa-list me-1"></i> Về danh sách hóa đơn
                </a>
            </div>
        </div>
    @endif

@endsection
