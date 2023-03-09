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
        $filename = str_replace("\\", "/", storage_path('app/public/wilayah.sql'));
        DB::unprepared(file_get_contents($filename));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::query('DROP TABLE IF EXISTS `provinsi`');
        \Illuminate\Support\Facades\DB::query('DROP TABLE IF EXISTS `kota_kabupaten`');
        \Illuminate\Support\Facades\DB::query('DROP TABLE IF EXISTS `kecamatan`');
        \Illuminate\Support\Facades\DB::query('DROP TABLE IF EXISTS `kelurahan`');
    }
};
