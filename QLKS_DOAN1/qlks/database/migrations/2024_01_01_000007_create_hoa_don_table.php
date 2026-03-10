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
        Schema::create('hoa_don', function (Blueprint $table) {
            $table->id('MaHoaDon');
            $table->unsignedBigInteger('MaPhong')->nullable();
            $table->unsignedBigInteger('MaDatPhong')->nullable();
            $table->date('NgayLapHD');
            $table->decimal('TongTien', 12, 2)->nullable();
            $table->timestamps();
            $table->foreign('MaPhong')
                  ->references('MaPhong')
                  ->on('phong')
                  ->onDelete('set null');
            $table->foreign('MaDatPhong')
                  ->references('MaDatPhong')
                  ->on('dat_phong')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hoa_don');
    }
};
