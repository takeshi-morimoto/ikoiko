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
        Schema::table('events', function (Blueprint $table) {
            // イベント通し番号（システム全体でユニーク）
            $table->string('event_code', 20)->unique()->after('id'); // 例: EV-2025-00001
            
            // スラッグの変更案：エリア-イベントタイプ-通し番号
            // 例: tokyo-anime-2025-00001, ikebukuro-anime-2025-00002, akihabara-anime-2025-00003
        });
        
        // 既存のslugカラムの長さを拡張（必要に応じて）
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug', 128)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('event_code');
        });
        
        Schema::table('events', function (Blueprint $table) {
            $table->string('slug', 64)->change();
        });
    }
};
