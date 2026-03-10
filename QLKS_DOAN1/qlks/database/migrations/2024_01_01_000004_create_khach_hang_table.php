<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('khach_hang', function (Blueprint $table) {
            $table->id('MaKhachHang');
            $table->unsignedBigInteger('MaTaiKhoan')->nullable();
            $table->string('TenKhachHang');
            $table->string('NgaySinh');
            $table->string('SoDienThoai');
            $table->string('DiaChi');
            $table->string('GiayChungMinh');
            $table->timestamps();

            $table->foreign('MaTaiKhoan')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('khach_hang');
    }
};
