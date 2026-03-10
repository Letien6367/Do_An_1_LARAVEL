<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'khach_hang';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaKhachHang';

    /**
     * Tên key cho route model binding
     */
    public function getRouteKeyName()
    {
        return 'MaKhachHang';
    }

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'MaTaiKhoan',
        'TenKhachHang',
        'NgaySinh',
        'SoDienThoai',
        'DiaChi',
        'GiayChungMinh',
    ];

    /**
     * Quan hệ với bảng User (n-1)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'MaTaiKhoan', 'id');
    }

    /**
     * Quan hệ với bảng DatPhong (1-n)
     */
    public function datPhong()
    {
        return $this->hasMany(DatPhong::class, 'MaKhachHang', 'MaKhachHang');
    }
}
