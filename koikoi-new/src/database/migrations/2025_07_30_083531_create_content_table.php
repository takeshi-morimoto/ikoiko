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
        // コンテンツ管理テーブル（固定ページ用）
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 100)->unique(); // URLスラッグ
            $table->string('title', 256); // ページタイトル
            $table->text('content'); // ページコンテンツ
            $table->string('template', 50)->default('default'); // 使用テンプレート
            $table->boolean('is_published')->default(true); // 公開状態
            
            // メタ情報
            $table->string('meta_title', 256)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image', 256)->nullable();
            
            $table->timestamps();
            
            $table->index('slug');
            $table->index('is_published');
        });
        
        // コンテンツブロック（再利用可能なコンテンツ）
        Schema::create('content_blocks', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique(); // 識別キー
            $table->string('name', 128); // ブロック名
            $table->text('content'); // コンテンツ
            $table->string('type', 20)->default('html'); // html, text, markdown
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_blocks');
        Schema::dropIfExists('pages');
    }
};
