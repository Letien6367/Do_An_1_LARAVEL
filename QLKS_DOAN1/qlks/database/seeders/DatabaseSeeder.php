<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TrangThaiPhong;
use App\Models\TrangThaiDatPhong;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo tài khoản Admin
        User::firstOrCreate(
            ['email' => 'admin@qlks.com'],
            [
                'name' => 'Quản trị viên',
                'password' => Hash::make('admin123'),
                'SoDienThoai' => '0123456789',
                'VaiTro' => 'admin',
            ]
        );

        // Tạo tài khoản User test
        User::firstOrCreate(
            ['email' => 'user@qlks.com'],
            [
                'name' => 'Người dùng',
                'password' => Hash::make('user123'),
                'SoDienThoai' => '0987654321',
                'VaiTro' => 'user',
            ]
        );

        // Tạo các trạng thái phòng mặc định
        $trangThaiPhongs = ['Trống', 'Đang sử dụng', 'Đang dọn dẹp', 'Bảo trì'];

        foreach ($trangThaiPhongs as $tenTrangThai) {
            TrangThaiPhong::firstOrCreate(['TenTrangThai' => $tenTrangThai]);
        }

        // Tạo các trạng thái đặt phòng mặc định
        $trangThaiDatPhongs = ['Chờ xác nhận', 'Đã xác nhận', 'Đang ở', 'Đã trả phòng', 'Đã hủy'];

        foreach ($trangThaiDatPhongs as $tenTrangThai) {
            TrangThaiDatPhong::firstOrCreate(['TenTrangThaiDP' => $tenTrangThai]);
        }
    }
}
