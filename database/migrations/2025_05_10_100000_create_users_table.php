<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->enum('role', [
                'admin', 'yayasan', 'lembaga_sd', 'lembaga_smp',
                'staff_sd', 'staff_smp', 'guru_sd', 'guru_smp',
                'walimurid_sd', 'walimurid_smp'
            ]);
            $table->boolean('isDeleted')->default(false);
            $table->timestamps();

            // Kolom relasi
            $table->string('id_pegawai', 30)->nullable(); // Sesuaikan tipe dengan pegawai.niy
            $table->unsignedBigInteger('id_walimurid')->nullable();

            // Foreign Key
            $table->foreign('id_pegawai')->references('niy')->on('pegawai')->onDelete('set null');
            $table->foreign('id_walimurid')->references('id')->on('walimurid')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
