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
        // イベント運営スケジュール
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->time('time');
            $table->string('activity', 200);
            $table->text('description')->nullable();
            $table->string('responsible_staff')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['event_id', 'time']);
        });

        // イベント備品管理
        Schema::create('event_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('item_name', 100);
            $table->integer('quantity');
            $table->string('status', 30)->default('pending'); // pending, prepared, checked
            $table->string('responsible_staff')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_rental')->default(false);
            $table->decimal('rental_cost', 10, 2)->nullable();
            $table->timestamps();
            
            $table->index(['event_id', 'status']);
        });

        // イベント役割分担
        Schema::create('event_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->nullable()->constrained('users');
            $table->string('role_name', 50); // 司会, 受付, カメラマン, サポート
            $table->text('responsibilities')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('status', 20)->default('assigned'); // assigned, confirmed, completed
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique(['event_id', 'staff_id', 'role_name']);
            $table->index(['event_id', 'status']);
        });

        // 座席・グループ管理
        Schema::create('event_seating', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('group_name', 50)->nullable();
            $table->integer('table_number')->nullable();
            $table->integer('seat_number');
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->string('special_notes')->nullable(); // 配慮事項
            $table->timestamps();
            
            $table->unique(['event_id', 'seat_number']);
            $table->index(['event_id', 'group_name']);
        });

        // 参加者特記事項
        Schema::create('customer_special_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('category', 30); // allergy, dietary, accessibility, other
            $table->text('details');
            $table->boolean('is_critical')->default(false);
            $table->boolean('is_confirmed')->default(false);
            $table->timestamps();
            
            $table->unique(['customer_id', 'event_id', 'category']);
            $table->index(['event_id', 'is_critical']);
        });

        // 会場レイアウト
        Schema::create('venue_layouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->nullable()->constrained();
            $table->string('layout_name', 100);
            $table->string('layout_type', 30); // theater, classroom, banquet, cocktail
            $table->integer('capacity');
            $table->json('layout_data')->nullable(); // JSON形式のレイアウト情報
            $table->string('image_url')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            $table->index(['venue_id', 'is_default']);
        });

        // 当日チェックリスト
        Schema::create('event_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('category', 50); // preparation, opening, closing
            $table->string('task', 200);
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->foreignId('completed_by')->nullable()->constrained('users');
            $table->integer('display_order')->default(0);
            $table->timestamps();
            
            $table->index(['event_id', 'category', 'is_completed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_checklists');
        Schema::dropIfExists('venue_layouts');
        Schema::dropIfExists('customer_special_notes');
        Schema::dropIfExists('event_seating');
        Schema::dropIfExists('event_roles');
        Schema::dropIfExists('event_equipment');
        Schema::dropIfExists('event_schedules');
    }
};