<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    use HasFactory;

    /**
     * Tên bảng trong database
     */
    protected $table = 'hoa_don';

    /**
     * Khóa chính của bảng
     */
    protected $primaryKey = 'MaHoaDon';

    /**
     * Các trường có thể gán giá trị
     */
    protected $fillable = [
        'MaPhong',
        'MaDatPhong',
        'NgayLapHD',
        'TongTien',
    ];

    /**
     * Các kiểu dữ liệu
     */
    protected $casts = [
        'NgayLapHD' => 'date',
        'TongTien' => 'decimal:2',
    ];

    /**
     * Quan hệ với bảng Phong (n-1)
     */
    public function phong()
    {
        return $this->belongsTo(Phong::class, 'MaPhong', 'MaPhong');
    }

    /**
     * Quan hệ với bảng DatPhong (n-1)
     */
    public function datPhong()
    {
        return $this->belongsTo(DatPhong::class, 'MaDatPhong', 'MaDatPhong');
    }
}
