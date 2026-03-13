<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'phong';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaPhong';

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'TenPhong',
        'SoNguoi',
        'GiaPhong',
        'MaTrangThai',
        'MaLoaiPhong',
    ];

    /**
     * Các kiểu dữ liệu
     */
    protected $casts = [
        'GiaPhong' => 'decimal:2',
        'SoNguoi' => 'integer',
    ];

    /**
     * Quan hệ với bảng LoaiPhong (n-1)
     */
    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'MaLoaiPhong', 'MaLoaiPhong');
    }

    /**
     * Quan hệ với bảng TrangThaiPhong (n-1)
     */
    public function trangThaiPhong()
    {
        return $this->belongsTo(TrangThaiPhong::class, 'MaTrangThai', 'MaTrangThai');
    }

    /**
     * Quan hệ với bảng DatPhong (1-n)
     * tại Phong có thể có nhiều DatPhong, nhưng mỗi DatPhong chỉ thuộc về một Phong
     */
    public function datPhong()
    {
        return $this->hasMany(DatPhong::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Quan hệ với bảng HoaDon (1-n)
     * tại Phong có thể có nhiều HoaDon, nhưng mỗi HoaDon chỉ thuộc về một Phong
     */
    public function hoaDon()
    {
        return $this->hasMany(HoaDon::class, 'MaPhong', 'MaPhong');
    }
}
