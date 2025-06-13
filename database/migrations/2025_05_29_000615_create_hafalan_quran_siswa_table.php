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
         Schema::create('hafalan_quran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_regis_mapel_siswa')->constrained('regis_mapel_siswa')->onDelete('cascade');
            $table->foreignId('id_surat')->constrained('master_surat')->onDelete('cascade');
            $table->foreignId('id_guru')->constrained('pegawai')->onDelete('cascade');
            $table->integer('ayat_dari')->check('ayat_dari > 0');
            $table->integer('ayat_sampai');
            $table->date('tgl_setor');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalan_quran_siswa');
    }
};
