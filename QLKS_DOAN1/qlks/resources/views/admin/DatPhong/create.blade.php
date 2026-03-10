@extends('layout.quanly')

@section('title', 'Đặt phòng mới')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dat-phong.index') }}">Đặt phòng</a></li>
            <li class="breadcrumb-item active">Đặt phòng mới</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Đặt phòng mới</h2>
            <p class="text-muted mb-0">Tạo đơn đặt phòng mới cho khách hàng</p>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="card shadow-sm" style="max-width: 800px;">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold"><i class="fas fa-calendar-plus text-primary me-2"></i>Thông tin đặt phòng</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.dat-phong.store') }}" method="POST" id="bookingForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Phòng <span class="text-danger">*</span></label>
                        <select name="MaPhong" id="phongSelect" class="form-select" required>
                            <option value="">-- Chọn phòng --</option>
                            @foreach($phongs as $phong)
                                <option value="{{ $phong->MaPhong }}"
                                        data-gia="{{ $phong->GiaPhong }}"
                                        data-loai="{{ $phong->loaiPhong->TenLoaiPhong ?? '' }}"
                                        data-songuoi="{{ $phong->SoNguoi }}"
                                        {{ old('MaPhong') == $phong->MaPhong ? 'selected' : '' }}>
                                    {{ $phong->TenPhong }} - {{ $phong->loaiPhong->TenLoaiPhong ?? '' }} ({{ number_format($phong->GiaPhong, 0, ',', '.') }} VNĐ/đêm)
                                </option>
                            @endforeach
                        </select>
                        @error('MaPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Khách hàng <span class="text-danger">*</span></label>
                        <select name="MaKhachHang" class="form-select" required>
                            <option value="">-- Chọn khách hàng --</option>
                            @foreach($khachHangs as $kh)
                                <option value="{{ $kh->MaKhachHang }}" {{ old('MaKhachHang') == $kh->MaKhachHang ? 'selected' : '' }}>
                                    {{ $kh->TenKhachHang }} - {{ $kh->SoDienThoai }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaKhachHang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày nhận phòng <span class="text-danger">*</span></label>
                        <input type="date" name="NgayDatPhong" id="ngayDat" class="form-control" value="{{ old('NgayDatPhong') }}" required>
                        @error('NgayDatPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày trả phòng <span class="text-danger">*</span></label>
                        <input type="date" name="NgayTraPhong" id="ngayTra" class="form-control" value="{{ old('NgayTraPhong') }}" required>
                        @error('NgayTraPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <select name="MaTrangThaiDP" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach($trangThais as $tt)
                                <option value="{{ $tt->MaTrangThaiDP }}" {{ old('MaTrangThaiDP') == $tt->MaTrangThaiDP ? 'selected' : '' }}>{{ $tt->TenTrangThaiDP }}</option>
                            @endforeach
                        </select>
                        @error('MaTrangThaiDP')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Room Preview -->
                <div class="card bg-light mt-4 d-none" id="roomPreview">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-bed me-2"></i>Thông tin phòng</h6>
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3"><i class="fas fa-bed fa-lg text-primary"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" id="previewRoomName">-</h6>
                                <small class="text-muted" id="previewRoomType">-</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success fs-5" id="previewPrice">0 VNĐ</span>
                                <br><small class="text-muted">/ đêm</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Summary -->
                <div class="card bg-light mt-3 d-none" id="bookingSummary">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Tóm tắt đặt phòng</h6>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Số đêm</span>
                            <span class="fw-semibold" id="summaryNights">0 đêm</span>
                        </div>
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Giá phòng</span>
                            <span class="fw-semibold" id="summaryRoomPrice">0 VNĐ/đêm</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2">
                            <span class="fw-bold">Tổng cộng (dự kiến)</span>
                            <span class="fw-bold text-success fs-5" id="summaryTotal">0 VNĐ</span>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.dat-phong.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Đặt phòng</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const phongSelect = document.getElementById('phongSelect');
    const ngayDat = document.getElementById('ngayDat');
    const ngayTra = document.getElementById('ngayTra');
    const roomPreview = document.getElementById('roomPreview');
    const bookingSummary = document.getElementById('bookingSummary');

    const today = new Date().toISOString().split('T')[0];
    ngayDat.setAttribute('min', today);

    phongSelect.addEventListener('change', updatePreview);
    ngayDat.addEventListener('change', function() { ngayTra.setAttribute('min', this.value); updateSummary(); });
    ngayTra.addEventListener('change', updateSummary);

    function updatePreview() {
        const selected = phongSelect.options[phongSelect.selectedIndex];
        if (selected.value) {
            roomPreview.classList.remove('d-none');
            document.getElementById('previewRoomName').textContent = selected.text.split(' - ')[0];
            document.getElementById('previewRoomType').textContent = selected.dataset.loai + ' | ' + selected.dataset.songuoi + ' người';
            document.getElementById('previewPrice').textContent = formatCurrency(selected.dataset.gia) + ' VNĐ';
            updateSummary();
        } else {
            roomPreview.classList.add('d-none');
            bookingSummary.classList.add('d-none');
        }
    }

    function updateSummary() {
        const selected = phongSelect.options[phongSelect.selectedIndex];
        if (selected.value && ngayDat.value && ngayTra.value) {
            const checkIn = new Date(ngayDat.value);
            const checkOut = new Date(ngayTra.value);
            const nights = Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24));
            if (nights > 0) {
                const price = parseFloat(selected.dataset.gia);
                bookingSummary.classList.remove('d-none');
                document.getElementById('summaryNights').textContent = nights + ' đêm';
                document.getElementById('summaryRoomPrice').textContent = formatCurrency(price) + ' VNĐ/đêm';
                document.getElementById('summaryTotal').textContent = formatCurrency(price * nights) + ' VNĐ';
            } else { bookingSummary.classList.add('d-none'); }
        } else { bookingSummary.classList.add('d-none'); }
    }

    function formatCurrency(value) { return new Intl.NumberFormat('vi-VN').format(value); }
    if (phongSelect.value) updatePreview();
</script>
@endpush
