<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mapel', function (Blueprint $table) {
            $table->enum('jenjang', [
                'SD ISLAM TERPADU INSAN MADANI',
                'SMP IT TAHFIDZUL QURAN INSAN MADANI'
            ])->after('nama_mapel')->default('SD ISLAM TERPADU INSAN MADANI');
        });
    }

    public function down(): void
    {
        Schema::table('mapel', function (Blueprint $table) {
            $table->dropColumn('jenjang');
        });
    }
};
