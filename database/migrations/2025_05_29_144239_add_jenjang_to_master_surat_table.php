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
    Schema::table('master_surat', function (Blueprint $table) {
        $table->enum('jenjang', [
            'SD ISLAM TERPADU INSAN MADANI',
            'SMP IT TAHFIDZUL QURAN INSAN MADANI'
        ])->after('jumlah_ayat')->default('SD ISLAM TERPADU INSAN MADANI');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('master_surat', function (Blueprint $table) {
        $table->dropColumn('jenjang');
    });
}
};
