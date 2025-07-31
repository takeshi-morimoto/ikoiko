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
        // スタッフプロフィール
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_code', 20)->unique()->nullable();
            $table->string('phone', 20);
            $table->string('emergency_contact', 20)->nullable();
            $table->date('hire_date');
            $table->string('employment_type', 30); // full_time, part_time, contract
            $table->json('skills')->nullable(); // ["MC", "受付", "撮影"]
            $table->json('qualifications')->nullable(); // 資格情報
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->unique('user_id');
            $table->index('is_active');
        });

        // スタッフシフト
        Schema::create('staff_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users');
            $table->foreignId('event_id')->nullable()->constrained();
            $table->date('shift_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->time('break_duration')->nullable();
            $table->string('shift_type', 30); // event, office, training, leave
            $table->string('status', 20)->default('scheduled'); // scheduled, confirmed, completed, cancelled
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['staff_id', 'shift_date']);
            $table->index(['event_id', 'shift_date']);
            $table->index(['shift_date', 'status']);
        });

        // シフト希望
        Schema::create('shift_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users');
            $table->date('request_date');
            $table->string('request_type', 20); // available, unavailable, preferred
            $table->time('preferred_start')->nullable();
            $table->time('preferred_end')->nullable();
            $table->text('reason')->nullable();
            $table->string('status', 20)->default('pending'); // pending, approved, rejected
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            $table->unique(['staff_id', 'request_date']);
            $table->index(['request_date', 'status']);
        });

        // スタッフ稼働実績
        Schema::create('staff_work_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users');
            $table->foreignId('shift_id')->nullable()->constrained('staff_shifts');
            $table->foreignId('event_id')->nullable()->constrained();
            $table->date('work_date');
            $table->time('actual_start');
            $table->time('actual_end');
            $table->time('break_time')->nullable();
            $table->decimal('work_hours', 5, 2);
            $table->decimal('overtime_hours', 5, 2)->default(0);
            $table->string('attendance_status', 20); // present, late, absent, leave
            $table->text('performance_notes')->nullable();
            $table->integer('performance_rating')->nullable(); // 1-5
            $table->foreignId('recorded_by')->nullable()->constrained('users');
            $table->timestamps();
            
            $table->unique(['staff_id', 'work_date', 'shift_id']);
            $table->index(['work_date', 'attendance_status']);
        });

        // スタッフスキル評価
        Schema::create('staff_skill_evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users');
            $table->string('skill_name', 50);
            $table->integer('skill_level'); // 1-5
            $table->date('evaluated_at');
            $table->foreignId('evaluated_by')->constrained('users');
            $table->text('evaluation_notes')->nullable();
            $table->timestamps();
            
            $table->unique(['staff_id', 'skill_name', 'evaluated_at']);
            $table->index(['staff_id', 'skill_level']);
        });

        // シフトテンプレート
        Schema::create('shift_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_name', 100);
            $table->string('event_type', 30)->nullable();
            $table->json('roles')->nullable(); // [{"role": "MC", "count": 1, "start": "09:00", "end": "18:00"}]
            $table->integer('total_staff_required');
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['event_type', 'is_active']);
        });

        // スタッフ通知
        Schema::create('staff_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained('users');
            $table->string('type', 30); // shift_assigned, shift_changed, shift_reminder
            $table->string('title', 200);
            $table->text('message');
            $table->json('data')->nullable(); // 関連データ
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamp('sent_at');
            $table->timestamps();
            
            $table->index(['staff_id', 'is_read', 'sent_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_notifications');
        Schema::dropIfExists('shift_templates');
        Schema::dropIfExists('staff_skill_evaluations');
        Schema::dropIfExists('staff_work_records');
        Schema::dropIfExists('shift_requests');
        Schema::dropIfExists('staff_shifts');
        Schema::dropIfExists('staff_profiles');
    }
};