<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrangThaiPhong extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'trang_thai_phong';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaTrangThai';

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'TenTrangThai',
    ];

    /**
     * Quan hệ với bảng Phong (1-n)
     */
    public function phong()
    {
        return $this->hasMany(Phong::class, 'MaTrangThai', 'MaTrangThai');
    }
}
