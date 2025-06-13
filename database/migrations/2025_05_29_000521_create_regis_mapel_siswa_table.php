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
        Schema::create('regis_mapel_siswa', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran')->onDelete('cascade');
        $table->foreignId('id_kelas')->constrained('kelas')->onDelete('cascade');
        $table->foreignId('id_mapel')->constrained('mapel')->onDelete('cascade');
        $table->foreignId('id_guru')->constrained('pegawai')->onDelete('cascade');
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('regis_mapel_siswa');
    }
};
