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
        // イベントタイプマスタ
        Schema::create('event_types', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 20)->unique(); // anime, machi, nazo
            $table->string('name', 50); // アニメコン、街コン、謎解き
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // イベントテーブル（1イベント1レコード）
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique(); // URLスラッグ（例: tokyo-anime-2025-08-15）
            $table->string('title', 128); // イベントタイトル
            $table->unsignedBigInteger('event_type_id'); // イベントタイプID
            $table->unsignedBigInteger('area_id'); // エリアID
            
            // 開催情報
            $table->date('event_date'); // 開催日
            $table->string('day_of_week', 2); // 曜日
            $table->time('start_time'); // 開始時間
            $table->time('end_time'); // 終了時間
            
            // 募集情報
            $table->integer('capacity_male')->nullable(); // 男性定員
            $table->integer('capacity_female')->nullable(); // 女性定員
            $table->integer('registered_male')->default(0); // 男性登録数
            $table->integer('registered_female')->default(0); // 女性登録数
            
            // 料金情報
            $table->integer('price_male')->nullable(); // 男性料金
            $table->integer('price_female')->nullable(); // 女性料金
            $table->integer('price_male_early')->nullable(); // 男性早割料金
            $table->integer('price_female_early')->nullable(); // 女性早割料金
            $table->date('early_deadline')->nullable(); // 早割締切日
            
            // 参加条件
            $table->integer('age_min_male')->nullable(); // 男性最低年齢
            $table->integer('age_max_male')->nullable(); // 男性最高年齢
            $table->integer('age_min_female')->nullable(); // 女性最低年齢
            $table->integer('age_max_female')->nullable(); // 女性最高年齢
            
            // 会場情報
            $table->string('venue_name', 128)->nullable(); // 会場名
            $table->string('venue_address', 256)->nullable(); // 会場住所
            $table->string('venue_url', 256)->nullable(); // 会場URL
            $table->text('venue_access')->nullable(); // アクセス方法
            $table->text('meeting_point')->nullable(); // 集合場所
            
            // 販売・PR情報
            $table->string('sales_copy', 128)->nullable(); // 販売コピー
            $table->string('pr_comment', 128)->nullable(); // PRコメント
            $table->text('description')->nullable(); // イベント説明
            $table->text('schedule')->nullable(); // タイムスケジュール
            $table->text('notes')->nullable(); // 注意事項
            
            // ステータス
            $table->enum('status', ['draft', 'published', 'cancelled', 'finished'])->default('draft');
            $table->boolean('is_accepting_male')->default(true); // 男性受付中
            $table->boolean('is_accepting_female')->default(true); // 女性受付中
            
            // メタ情報（SEO用）
            $table->string('meta_title', 256)->nullable();
            $table->text('meta_description')->nullable();
            $table->string('og_image', 256)->nullable();
            
            $table->timestamps();
            
            // インデックス
            $table->foreign('event_type_id')->references('id')->on('event_types');
            $table->foreign('area_id')->references('id')->on('areas');
            $table->index(['event_date', 'status']);
            $table->index(['event_type_id', 'area_id', 'event_date']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
        Schema::dropIfExists('event_types');
    }
};
