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
            // セッション情報を追加（同日複数開催対応）
            $table->string('session_name', 50)->nullable()->after('end_time'); // 昼の部、夜の部など
            $table->integer('session_number')->default(1)->after('session_name'); // その日の何回目か
            
            // スラッグのユニーク制約を確認（既存のまま維持）
            // 時間帯を含むスラッグで差別化（例: tokyo-anime-2025-08-15-afternoon）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['session_name', 'session_number']);
        });
    }
};
