<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrangThaiDatPhong extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'trang_thai_dat_phong';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaTrangThaiDP';

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'TenTrangThaiDP',
    ];

    /**
     * Quan hệ với bảng DatPhong (1-n)
     */
    public function datPhong()
    {
        return $this->hasMany(DatPhong::class, 'MaTrangThaiDP', 'MaTrangThaiDP');
    }
}
