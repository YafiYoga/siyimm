<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi');
            $table->string('ditujukan_kepada'); // otomatis: walimurid_sd / walimurid_smp
            $table->unsignedBigInteger('dibuat_oleh'); // user_id pembuat
            $table->string('role_pembuat'); // staff_sd / staff_smp
            $table->timestamps();

            $table->foreign('dibuat_oleh')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengumuman');
    }
};
