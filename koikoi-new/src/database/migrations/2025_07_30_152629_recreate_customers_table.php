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
        // 既存のテーブルを削除
        Schema::dropIfExists('customers');
        
        // 新しい構造で再作成
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('registration_number', 20)->unique();
            
            // 基本情報
            $table->string('name', 100);
            $table->string('name_kana', 100);
            $table->string('email', 255);
            $table->string('phone', 20);
            $table->enum('gender', ['male', 'female']);
            $table->date('birth_date');
            $table->integer('age');
            
            // 住所
            $table->string('postal_code', 10);
            $table->string('prefecture', 20);
            $table->string('city', 100);
            $table->string('address', 255);
            
            // 緊急連絡先
            $table->string('emergency_contact', 20)->nullable();
            $table->string('emergency_name', 100)->nullable();
            
            // その他
            $table->text('comment')->nullable();
            $table->string('status', 20)->default('registered');
            $table->string('payment_method', 20)->nullable();
            $table->string('payment_status', 20)->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->text('cancel_reason')->nullable();
            
            $table->timestamps();
            
            // インデックス
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->index(['event_id', 'status']);
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
