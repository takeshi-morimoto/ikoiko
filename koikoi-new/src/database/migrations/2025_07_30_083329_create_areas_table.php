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
        // 都道府県マスタ
        Schema::create('prefectures', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique(); // 都道府県コード
            $table->string('name', 10); // 都道府県名
            $table->integer('display_order')->default(0); // 表示順
            $table->timestamps();
        });

        // エリアマスタ（地域・会場情報）
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 32)->unique(); // URLスラッグ（例: tokyo, yokohama）
            $table->string('name', 32); // エリア名（例: 東京、横浜）
            $table->unsignedBigInteger('prefecture_id'); // 都道府県ID
            $table->string('place', 64)->nullable(); // 会場・場所
            $table->text('description')->nullable(); // エリア説明
            $table->boolean('is_active')->default(true); // 有効/無効
            $table->integer('display_order')->default(0); // 表示順
            $table->timestamps();
            
            $table->foreign('prefecture_id')->references('id')->on('prefectures');
            $table->index(['is_active', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
        Schema::dropIfExists('prefectures');
    }
};
