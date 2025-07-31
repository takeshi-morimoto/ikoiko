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
        // イベント売上集計
        Schema::create('event_revenue_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('male_revenue', 12, 2)->default(0);
            $table->decimal('female_revenue', 12, 2)->default(0);
            $table->decimal('early_bird_revenue', 12, 2)->default(0);
            $table->decimal('regular_revenue', 12, 2)->default(0);
            $table->decimal('cancellation_fees', 12, 2)->default(0);
            $table->integer('paid_male_count')->default(0);
            $table->integer('paid_female_count')->default(0);
            $table->integer('unpaid_count')->default(0);
            $table->decimal('collection_rate', 5, 2)->default(0); // 回収率
            $table->date('calculated_at');
            $table->timestamps();
            
            $table->unique(['event_id', 'calculated_at']);
            $table->index('calculated_at');
        });

        // 参加者分析データ
        Schema::create('event_participant_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->integer('total_registered')->default(0);
            $table->integer('male_registered')->default(0);
            $table->integer('female_registered')->default(0);
            $table->integer('total_attended')->default(0);
            $table->integer('male_attended')->default(0);
            $table->integer('female_attended')->default(0);
            $table->integer('no_show_count')->default(0);
            $table->integer('cancelled_count')->default(0);
            $table->decimal('attendance_rate', 5, 2)->default(0);
            $table->decimal('male_female_ratio', 5, 2)->default(0);
            $table->json('age_distribution')->nullable(); // {"20-24": 10, "25-29": 15, ...}
            $table->json('prefecture_distribution')->nullable(); // {"tokyo": 20, "osaka": 10, ...}
            $table->decimal('satisfaction_score', 3, 2)->nullable(); // 満足度スコア
            $table->timestamps();
            
            $table->unique('event_id');
        });

        // 顧客分析サマリー
        Schema::create('customer_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->integer('total_events_registered')->default(0);
            $table->integer('total_events_attended')->default(0);
            $table->integer('total_cancellations')->default(0);
            $table->decimal('attendance_rate', 5, 2)->default(0);
            $table->decimal('total_spent', 12, 2)->default(0);
            $table->decimal('average_spent_per_event', 10, 2)->default(0);
            $table->date('first_event_date')->nullable();
            $table->date('last_event_date')->nullable();
            $table->integer('days_since_last_event')->nullable();
            $table->string('customer_segment', 30)->nullable(); // new, regular, vip, dormant
            $table->decimal('lifetime_value', 12, 2)->default(0);
            $table->json('preferred_event_types')->nullable(); // {"anime": 5, "machi": 3}
            $table->json('preferred_areas')->nullable(); // {"tokyo": 8, "yokohama": 2}
            $table->timestamps();
            
            $table->unique('customer_id');
            $table->index('customer_segment');
            $table->index('last_event_date');
        });

        // 月次集計データ
        Schema::create('monthly_summaries', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->unsignedTinyInteger('month');
            $table->string('event_type_slug', 20)->nullable(); // null=全体集計
            $table->integer('total_events')->default(0);
            $table->integer('total_participants')->default(0);
            $table->decimal('total_revenue', 12, 2)->default(0);
            $table->decimal('average_participants_per_event', 8, 2)->default(0);
            $table->decimal('average_revenue_per_event', 10, 2)->default(0);
            $table->decimal('male_female_ratio', 5, 2)->default(0);
            $table->integer('new_customers')->default(0);
            $table->integer('repeat_customers')->default(0);
            $table->decimal('repeat_rate', 5, 2)->default(0);
            $table->json('top_areas')->nullable(); // [{"area": "tokyo", "count": 10}, ...]
            $table->timestamps();
            
            $table->unique(['year', 'month', 'event_type_slug']);
            $table->index(['year', 'month']);
        });

        // クーポン・割引利用履歴
        Schema::create('discount_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('event_id')->constrained();
            $table->string('discount_code', 50);
            $table->string('discount_type', 30); // percentage, fixed, early_bird
            $table->decimal('discount_amount', 10, 2);
            $table->decimal('original_price', 10, 2);
            $table->decimal('final_price', 10, 2);
            $table->timestamp('used_at');
            $table->timestamps();
            
            $table->index(['discount_code', 'used_at']);
            $table->index(['customer_id', 'event_id']);
        });

        // KPIトラッキング
        Schema::create('kpi_tracking', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('kpi_name', 50); // conversion_rate, avg_revenue_per_user, etc
            $table->decimal('value', 12, 4);
            $table->string('dimension', 30)->nullable(); // event_type, area, etc
            $table->string('dimension_value', 50)->nullable();
            $table->timestamps();
            
            $table->unique(['date', 'kpi_name', 'dimension', 'dimension_value']);
            $table->index(['kpi_name', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kpi_tracking');
        Schema::dropIfExists('discount_usages');
        Schema::dropIfExists('monthly_summaries');
        Schema::dropIfExists('customer_analytics');
        Schema::dropIfExists('event_participant_analytics');
        Schema::dropIfExists('event_revenue_summaries');
    }
};