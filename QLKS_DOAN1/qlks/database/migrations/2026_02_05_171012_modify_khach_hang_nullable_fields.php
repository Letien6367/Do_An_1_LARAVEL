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
        Schema::table('khach_hang', function (Blueprint $table) {
            $table->string('NgaySinh')->nullable()->change();
            $table->string('SoDienThoai')->nullable()->change();
            $table->string('DiaChi')->nullable()->change();
            $table->string('GiayChungMinh')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('khach_hang', function (Blueprint $table) {
            $table->string('NgaySinh')->nullable(false)->change();
            $table->string('SoDienThoai')->nullable(false)->change();
            $table->string('DiaChi')->nullable(false)->change();
            $table->string('GiayChungMinh')->nullable(false)->change();
        });
    }
};
