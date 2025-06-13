<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id(); // Kolom id sebagai primary key auto-increment
            $table->string('niy', 30)->unique(); // Niy tetap unik, tapi bukan primary key
            $table->string('unit_kerja')->nullable();
            $table->string('nama_lengkap');
            $table->string('nama_panggilan')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->string('tempat_tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telfon')->nullable()->nullable();
            $table->string('email')->nullable();
            $table->date('tmt')->nullable();
            $table->string('tugas_kepegawaian')->nullable();
            $table->string('tugas_pokok')->nullable();
            $table->string('status_pernikahan')->nullable();
            $table->string('nama_pasangan')->nullable();
            $table->text('nama_anak')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('pendidikan_terakhir')->nullable();
            $table->text('pas_foto_url')->nullable();
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
