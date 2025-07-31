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
        Schema::table('areas', function (Blueprint $table) {
            // より詳細な地域情報を追加
            $table->string('district', 32)->nullable()->after('name'); // 区・市（例: 豊島区、千代田区）
            $table->string('station', 64)->nullable()->after('district'); // 最寄り駅（例: 池袋駅、秋葉原駅）
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn(['district', 'station']);
        });
    }
};
