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
        Schema::create('absensi_siswa', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_regis_mapel_siswa')->constrained('regis_mapel_siswa')->onDelete('cascade');
        $table->date('tanggal');
        $table->enum('status', ['hadir', 'sakit', 'alpha', 'izin']);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_siswa');
    }
};
