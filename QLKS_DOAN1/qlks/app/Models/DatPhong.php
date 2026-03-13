<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatPhong extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'dat_phong';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaDatPhong';

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'MaPhong',
        'MaKhachHang',
        'NgayDatPhong',
        'NgayTraPhong',
        'MaTrangThaiDP',
    ];

    /**
     * Các kiểu dữ liệu
     */
    protected $casts = [
        'NgayDatPhong' => 'date',
        'NgayTraPhong' => 'date',
    ];

    /**
     * Quan hệ với bảng Phong (n-1)
     */
    public function phong()
    {
        return $this->belongsTo(Phong::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Quan hệ với bảng KhachHang (n-1)
     */
    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'MaKhachHang', 'MaKhachHang');
    }

    /**
     * Quan hệ với bảng TrangThaiDatPhong (n-1)
     */
    public function trangThaiDatPhong()
    {
        return $this->belongsTo(TrangThaiDatPhong::class, 'MaTrangThaiDP', 'MaTrangThaiDP');
    }

    /**
     * Quan hệ với bảng HoaDon (1-n)
     * tại DatPhong có thể có nhiều HoaDon, nhưng mỗi HoaDon chỉ thuộc về một DatPhong
     */
    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaDatPhong', 'MaDatPhong');
    }
}
