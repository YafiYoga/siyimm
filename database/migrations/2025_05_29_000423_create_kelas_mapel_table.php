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
        Schema::create('kelas_mapel', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel tahun_ajaran
            $table->unsignedBigInteger('id_tahun_ajaran');
            $table->foreign('id_tahun_ajaran')
                ->references('id')
                ->on('tahun_ajaran')
                ->onDelete('cascade');

            // Relasi ke tabel kelas
            $table->unsignedBigInteger('id_kelas');
            $table->foreign('id_kelas')
                ->references('id')
                ->on('kelas')
                ->onDelete('cascade');

            // Relasi ke tabel mapel
            $table->unsignedBigInteger('id_mapel');
            $table->foreign('id_mapel')
                ->references('id')
                ->on('mapel')
                ->onDelete('cascade');

            // Relasi ke tabel pegawai
            $table->unsignedBigInteger('id_guru');
            $table->foreign('id_guru')
                ->references('id')
                ->on('pegawai')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_mapel');
    }
};
