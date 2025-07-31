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
        // 顧客（参加者）テーブル
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('registration_code', 64)->unique(); // 登録コード（旧find）
            $table->unsignedBigInteger('event_id'); // イベントID
            
            // 基本情報
            $table->string('name', 32); // 名前
            $table->string('furigana', 32); // ふりがな
            $table->enum('gender', ['male', 'female']); // 性別
            $table->integer('age'); // 年齢
            $table->string('email', 128); // メールアドレス
            $table->string('phone', 32); // 電話番号
            
            // 参加情報
            $table->integer('party_size')->default(1); // 参加人数
            $table->datetime('registered_at'); // 登録日時
            $table->enum('registration_status', ['pending', 'confirmed', 'cancelled'])->default('pending'); // 登録状態
            
            // 支払い情報
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid'); // 支払い状態
            $table->date('payment_date')->nullable(); // 支払い日
            $table->integer('payment_amount')->nullable(); // 支払い金額
            $table->string('payment_method', 20)->nullable(); // 支払い方法
            
            // その他
            $table->text('memo')->nullable(); // メモ
            $table->boolean('is_downloaded')->default(false); // ダウンロード済み（CSV出力用）
            
            $table->timestamps();
            
            // インデックス
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->index(['event_id', 'registration_status']);
            $table->index(['email', 'event_id']);
            $table->index('registered_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
