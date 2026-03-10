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
        Schema::create('phong', function (Blueprint $table) {
            $table->id('MaPhong');
            $table->string('TenPhong');
            $table->integer('SoNguoi');
            $table->decimal('GiaPhong', 12, 2)->nullable();
            $table->unsignedBigInteger('MaTrangThai');
            $table->unsignedBigInteger('MaLoaiPhong');
            $table->timestamps();

            $table->foreign('MaTrangThai')
                  ->references('MaTrangThai')
                  ->on('trang_thai_phong')
                  ->onDelete('cascade');

            $table->foreign('MaLoaiPhong')
                  ->references('MaLoaiPhong')
                  ->on('loai_phong')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('phong');
    }
};
