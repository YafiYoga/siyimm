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
    Schema::table('regis_mapel_siswa', function (Blueprint $table) {
        // Drop kolom lama
        $table->dropForeign(['id_tahun_ajaran']);
        $table->dropColumn('id_tahun_ajaran');

        $table->dropForeign(['id_kelas']);
        $table->dropColumn('id_kelas');

        $table->dropForeign(['id_mapel']);
        $table->dropColumn('id_mapel');

        $table->dropForeign(['id_guru']);
        $table->dropColumn('id_guru');

        // Tambah kolom baru
        $table->unsignedBigInteger('id_kelas_mapel')->after('id');
        $table->foreign('id_kelas_mapel')->references('id')->on('kelas_mapel')->onDelete('cascade');

        $table->unsignedBigInteger('id_siswa')->after('id_kelas_mapel');
        $table->foreign('id_siswa')->references('id')->on('siswa')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('regis_mapel_siswa', function (Blueprint $table) {
        // Drop kolom baru
        $table->dropForeign(['id_kelas_mapel']);
        $table->dropColumn('id_kelas_mapel');

        $table->dropForeign(['id_siswa']);
        $table->dropColumn('id_siswa');

        // Tambah kembali kolom lama
        $table->foreignId('id_tahun_ajaran')->constrained('tahun_ajaran')->onDelete('cascade');
        $table->foreignId('id_kelas')->constrained('kelas')->onDelete('cascade');
        $table->foreignId('id_mapel')->constrained('mapel')->onDelete('cascade');
        $table->foreignId('id_guru')->constrained('pegawai')->onDelete('cascade');
    });
}

};
