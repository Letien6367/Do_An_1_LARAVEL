<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update only the default value, existing records are untouched.
        DB::statement("ALTER TABLE users ALTER COLUMN VaiTro SET DEFAULT 'KhachHang'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users ALTER COLUMN VaiTro SET DEFAULT 'user'");
    }
};
