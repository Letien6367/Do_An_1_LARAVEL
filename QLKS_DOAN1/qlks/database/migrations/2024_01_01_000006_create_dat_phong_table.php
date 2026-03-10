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
        Schema::create('dat_phong', function (Blueprint $table) {
            $table->id('MaDatPhong');
            $table->unsignedBigInteger('MaPhong')->nullable();
            $table->unsignedBigInteger('MaKhachHang')->nullable();
            $table->date('NgayDatPhong');
            $table->date('NgayTraPhong');
            $table->unsignedBigInteger('MaTrangThaiDP')->nullable();
            $table->timestamps();

            $table->foreign('MaPhong')
                  ->references('MaPhong')
                  ->on('phong')
                  ->onDelete('set null');

            $table->foreign('MaKhachHang')
                  ->references('MaKhachHang')
                  ->on('khach_hang')
                  ->onDelete('set null');

            $table->foreign('MaTrangThaiDP')
                  ->references('MaTrangThaiDP')
                  ->on('trang_thai_dat_phong')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dat_phong');
    }
};
