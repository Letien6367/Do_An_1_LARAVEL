@extends('layout.quanly')

@section('title', 'Sửa đặt phòng')

@section('content')
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.dat-phong.index') }}">Đặt phòng</a></li>
            <li class="breadcrumb-item active">Sửa #{{ $datPhong->MaDatPhong }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-1">Sửa đặt phòng</h2>
            <p class="text-muted mb-0">Cập nhật thông tin đặt phòng</p>
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
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold"><i class="fas fa-edit text-warning me-2"></i>Thông tin đặt phòng</h5>
            <span class="badge bg-secondary">ID: #{{ $datPhong->MaDatPhong }}</span>
        </div>
        <div class="card-body">
            <!-- Info Box -->
            <div class="alert alert-light border mb-4">
                <h6 class="fw-bold mb-2"><i class="fas fa-info-circle me-1"></i> Thông tin hiện tại</h6>
                <div class="row">
                    <div class="col-md-6"><small class="text-muted">Ngày tạo:</small> <strong>{{ $datPhong->created_at ? $datPhong->created_at->format('d/m/Y H:i') : 'N/A' }}</strong></div>
                    <div class="col-md-6"><small class="text-muted">Cập nhật:</small> <strong>{{ $datPhong->updated_at ? $datPhong->updated_at->format('d/m/Y H:i') : 'N/A' }}</strong></div>
                </div>
            </div>

            <form action="{{ route('admin.dat-phong.update', $datPhong->MaDatPhong) }}" method="POST">
                @csrf
                @method('PUT')
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
                                        {{ old('MaPhong', $datPhong->MaPhong) == $phong->MaPhong ? 'selected' : '' }}>
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
                                <option value="{{ $kh->MaKhachHang }}" {{ old('MaKhachHang', $datPhong->MaKhachHang) == $kh->MaKhachHang ? 'selected' : '' }}>
                                    {{ $kh->TenKhachHang }} - {{ $kh->SoDienThoai }}
                                </option>
                            @endforeach
                        </select>
                        @error('MaKhachHang')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày nhận phòng <span class="text-danger">*</span></label>
                        <input type="date" name="NgayDatPhong" id="ngayDat" class="form-control"
                               value="{{ old('NgayDatPhong', $datPhong->NgayDatPhong ? $datPhong->NgayDatPhong->format('Y-m-d') : '') }}" required>
                        @error('NgayDatPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Ngày trả phòng <span class="text-danger">*</span></label>
                        <input type="date" name="NgayTraPhong" id="ngayTra" class="form-control"
                               value="{{ old('NgayTraPhong', $datPhong->NgayTraPhong ? $datPhong->NgayTraPhong->format('Y-m-d') : '') }}" required>
                        @error('NgayTraPhong')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Trạng thái <span class="text-danger">*</span></label>
                        <select name="MaTrangThaiDP" class="form-select" required>
                            <option value="">-- Chọn trạng thái --</option>
                            @foreach($trangThais as $tt)
                                <option value="{{ $tt->MaTrangThaiDP }}" {{ old('MaTrangThaiDP', $datPhong->MaTrangThaiDP) == $tt->MaTrangThaiDP ? 'selected' : '' }}>{{ $tt->TenTrangThaiDP }}</option>
                            @endforeach
                        </select>
                        @error('MaTrangThaiDP')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Room Preview -->
                <div class="card bg-light mt-4" id="roomPreview">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3"><i class="fas fa-bed me-2"></i>Thông tin phòng</h6>
                        <div class="d-flex align-items-center">
                            <div class="rounded-3 bg-primary bg-opacity-10 p-3 me-3"><i class="fas fa-bed fa-lg text-primary"></i></div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold" id="previewRoomName">{{ $datPhong->phong->TenPhong ?? '-' }}</h6>
                                <small class="text-muted" id="previewRoomType">{{ $datPhong->phong->loaiPhong->TenLoaiPhong ?? '' }} | {{ $datPhong->phong->SoNguoi ?? 0 }} người</small>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold text-success fs-5" id="previewPrice">{{ number_format($datPhong->phong->GiaPhong ?? 0, 0, ',', '.') }} VNĐ</span>
                                <br><small class="text-muted">/ đêm</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('admin.dat-phong.index') }}" class="btn btn-secondary"><i class="fas fa-arrow-left me-1"></i> Quay lại</a>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save me-1"></i> Cập nhật</button>
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

    phongSelect.addEventListener('change', updatePreview);
    ngayDat.addEventListener('change', function() { ngayTra.setAttribute('min', this.value); });

    function updatePreview() {
        const selected = phongSelect.options[phongSelect.selectedIndex];
        if (selected.value) {
            document.getElementById('previewRoomName').textContent = selected.text.split(' - ')[0];
            document.getElementById('previewRoomType').textContent = selected.dataset.loai + ' | ' + selected.dataset.songuoi + ' người';
            document.getElementById('previewPrice').textContent = formatCurrency(selected.dataset.gia) + ' VNĐ';
        }
    }

    function formatCurrency(value) { return new Intl.NumberFormat('vi-VN').format(value); }
</script>
@endpush
