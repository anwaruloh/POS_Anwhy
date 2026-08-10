<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah tipe kolom enum di MySQL langsung
        DB::statement("ALTER TABLE penjualan MODIFY COLUMN status ENUM('OPEN', 'DRAFT', 'COMPLETED') DEFAULT 'OPEN'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE penjualan MODIFY COLUMN status ENUM('OPEN', 'COMPLETED') DEFAULT 'OPEN'");
    }
};
