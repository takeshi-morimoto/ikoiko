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
        Schema::table('prefectures', function (Blueprint $table) {
            // 都道府県の英語コード（URL用）を追加
            $table->string('code_en', 20)->unique()->after('code'); // tokyo, osaka, kanagawa等
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prefectures', function (Blueprint $table) {
            $table->dropColumn('code_en');
        });
    }
};
