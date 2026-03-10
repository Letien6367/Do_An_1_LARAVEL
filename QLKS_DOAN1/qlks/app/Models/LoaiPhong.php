<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiPhong extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'loai_phong';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaLoaiPhong';

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'TenLoaiPhong',
    ];

    /**
     * Quan hệ với bảng Phong (1-n)
     */
    public function phong()
    {
        return $this->hasMany(Phong::class, 'MaLoaiPhong', 'MaLoaiPhong');
    }
}
