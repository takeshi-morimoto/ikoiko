<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * データベースインデックスの最適化
     */
    public function up(): void
    {
        // events テーブルのインデックス最適化
        Schema::table('events', function (Blueprint $table) {
            // 複合インデックス（よく一緒に検索される項目）
            if (!Schema::hasIndex('events', 'idx_events_type_status_date')) {
                $table->index(['event_type', 'status', 'event_date'], 'idx_events_type_status_date');
            }
            if (!Schema::hasIndex('events', 'idx_events_area_date')) {
                $table->index(['area_id', 'event_date'], 'idx_events_area_date');
            }
            if (!Schema::hasIndex('events', 'idx_events_prefecture_date')) {
                $table->index(['prefecture_id', 'event_date'], 'idx_events_prefecture_date');
            }
            
            // 単一インデックス（頻繁に検索される項目）
            if (!Schema::hasIndex('events', 'idx_events_date')) {
                $table->index('event_date', 'idx_events_date');
            }
            if (!Schema::hasIndex('events', 'idx_events_status')) {
                $table->index('status', 'idx_events_status');
            }
            if (!Schema::hasIndex('events', 'idx_events_featured')) {
                $table->index('is_featured', 'idx_events_featured');
            }
            if (!Schema::hasIndex('events', 'idx_events_limit')) {
                $table->index('participant_limit', 'idx_events_limit');
            }
            
            // ソート用インデックス
            if (!Schema::hasIndex('events', 'idx_events_created_id')) {
                $table->index(['created_at', 'id'], 'idx_events_created_id');
            }
            if (!Schema::hasIndex('events', 'idx_events_views_id')) {
                $table->index(['view_count', 'id'], 'idx_events_views_id');
            }
        });
        
        // areas テーブルのインデックス最適化
        Schema::table('areas', function (Blueprint $table) {
            // 複合インデックス
            if (!Schema::hasIndex('areas', 'idx_areas_prefecture_active')) {
                $table->index(['prefecture_id', 'is_active'], 'idx_areas_prefecture_active');
            }
            
            // 単一インデックス
            if (!Schema::hasIndex('areas', 'idx_areas_slug')) {
                $table->index('slug', 'idx_areas_slug');
            }
            if (!Schema::hasIndex('areas', 'idx_areas_active')) {
                $table->index('is_active', 'idx_areas_active');
            }
            if (!Schema::hasIndex('areas', 'idx_areas_sort')) {
                $table->index('sort_order', 'idx_areas_sort');
            }
            
            // 検索用インデックス（部分一致検索の高速化）
            if (!Schema::hasIndex('areas', 'idx_areas_name')) {
                $table->index('name', 'idx_areas_name');
            }
            if (!Schema::hasIndex('areas', 'idx_areas_name_kana')) {
                $table->index('name_kana', 'idx_areas_name_kana');
            }
        });
        
        // customers テーブルのインデックス最適化
        Schema::table('customers', function (Blueprint $table) {
            // ユニークインデックス
            if (!Schema::hasColumn('customers', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasIndex('customers', 'uniq_customers_email')) {
                $table->unique('email', 'uniq_customers_email');
            }
            
            // 複合インデックス
            if (!Schema::hasIndex('customers', 'idx_customers_event_status')) {
                $table->index(['event_id', 'status'], 'idx_customers_event_status');
            }
            if (!Schema::hasIndex('customers', 'idx_customers_created_status')) {
                $table->index(['created_at', 'status'], 'idx_customers_created_status');
            }
            
            // 単一インデックス
            if (!Schema::hasIndex('customers', 'idx_customers_event')) {
                $table->index('event_id', 'idx_customers_event');
            }
            if (!Schema::hasIndex('customers', 'idx_customers_status')) {
                $table->index('status', 'idx_customers_status');
            }
            if (!Schema::hasIndex('customers', 'idx_customers_payment')) {
                $table->index('payment_status', 'idx_customers_payment');
            }
            if (!Schema::hasIndex('customers', 'idx_customers_created')) {
                $table->index('created_at', 'idx_customers_created');
            }
        });
        
        // users テーブルのインデックス最適化
        Schema::table('users', function (Blueprint $table) {
            // ログイン用インデックス
            if (!Schema::hasIndex('users', 'idx_users_created')) {
                $table->index('created_at', 'idx_users_created');
            }
            
            // ソフトデリート用インデックス（もし使用している場合）
            if (Schema::hasColumn('users', 'deleted_at') && !Schema::hasIndex('users', 'idx_users_deleted')) {
                $table->index('deleted_at', 'idx_users_deleted');
            }
        });
        
        // content テーブルのインデックス最適化
        if (Schema::hasTable('content')) {
            Schema::table('content', function (Blueprint $table) {
                // 複合インデックス
                $table->index(['page_type', 'status'], 'idx_content_type_status');
                $table->index(['section', 'sort_order'], 'idx_content_section_sort');
                
                // 単一インデックス
                $table->index('page_type', 'idx_content_page_type');
                $table->index('section', 'idx_content_section');
                $table->index('status', 'idx_content_status');
                $table->index('sort_order', 'idx_content_sort');
            });
        }
        
        // sessions テーブルのインデックス最適化（使用している場合）
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->index('user_id', 'idx_sessions_user');
                $table->index('last_activity', 'idx_sessions_activity');
            });
        }
        
        // cache テーブルのインデックス最適化（使用している場合）
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                // keyカラムが既にプライマリキーでない場合
                if (!Schema::hasIndex('cache', 'cache_key_unique')) {
                    $table->unique('key', 'cache_key_unique');
                }
                $table->index('expiration', 'idx_cache_expiration');
            });
        }
        
        // jobs テーブルのインデックス最適化（使用している場合）
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->index(['queue', 'reserved_at'], 'idx_jobs_queue_reserved');
                $table->index('available_at', 'idx_jobs_available');
            });
        }
        
        // prefectures テーブルのインデックス最適化
        if (Schema::hasTable('prefectures')) {
            Schema::table('prefectures', function (Blueprint $table) {
                $table->index('region', 'idx_prefectures_region');
                $table->index('code', 'idx_prefectures_code');
                $table->index('name', 'idx_prefectures_name');
            });
        }
        
        // event_registrations テーブルのインデックス最適化（存在する場合）
        if (Schema::hasTable('event_registrations')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->index(['event_id', 'status'], 'idx_registrations_event_status');
                $table->index(['customer_id', 'event_id'], 'idx_registrations_customer_event');
                $table->index('created_at', 'idx_registrations_created');
            });
        }
        
        // event_participants テーブルのインデックス最適化（存在する場合）
        if (Schema::hasTable('event_participants')) {
            Schema::table('event_participants', function (Blueprint $table) {
                $table->index(['event_id', 'participation_status'], 'idx_participants_event_status');
                $table->index('check_in_time', 'idx_participants_checkin');
            });
        }
        
        // staff_schedules テーブルのインデックス最適化（存在する場合）
        if (Schema::hasTable('staff_schedules')) {
            Schema::table('staff_schedules', function (Blueprint $table) {
                $table->index(['event_id', 'staff_id'], 'idx_schedules_event_staff');
                $table->index(['shift_date', 'shift_start'], 'idx_schedules_date_start');
            });
        }
    }

    /**
     * マイグレーションをロールバック
     */
    public function down(): void
    {
        // events テーブルのインデックス削除
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex('idx_events_type_status_date');
            $table->dropIndex('idx_events_area_date');
            $table->dropIndex('idx_events_prefecture_date');
            $table->dropIndex('idx_events_date');
            $table->dropIndex('idx_events_status');
            $table->dropIndex('idx_events_featured');
            $table->dropIndex('idx_events_limit');
            $table->dropIndex('idx_events_created_id');
            $table->dropIndex('idx_events_views_id');
        });
        
        // areas テーブルのインデックス削除
        Schema::table('areas', function (Blueprint $table) {
            $table->dropIndex('idx_areas_prefecture_active');
            $table->dropIndex('idx_areas_slug');
            $table->dropIndex('idx_areas_active');
            $table->dropIndex('idx_areas_sort');
            $table->dropIndex('idx_areas_name');
            $table->dropIndex('idx_areas_name_kana');
        });
        
        // customers テーブルのインデックス削除
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex('uniq_customers_email');
            $table->dropIndex('idx_customers_event_status');
            $table->dropIndex('idx_customers_created_status');
            $table->dropIndex('idx_customers_event');
            $table->dropIndex('idx_customers_status');
            $table->dropIndex('idx_customers_payment');
            $table->dropIndex('idx_customers_created');
        });
        
        // users テーブルのインデックス削除
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_created');
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropIndex('idx_users_deleted');
            }
        });
        
        // content テーブルのインデックス削除
        if (Schema::hasTable('content')) {
            Schema::table('content', function (Blueprint $table) {
                $table->dropIndex('idx_content_type_status');
                $table->dropIndex('idx_content_section_sort');
                $table->dropIndex('idx_content_page_type');
                $table->dropIndex('idx_content_section');
                $table->dropIndex('idx_content_status');
                $table->dropIndex('idx_content_sort');
            });
        }
        
        // sessions テーブルのインデックス削除
        if (Schema::hasTable('sessions')) {
            Schema::table('sessions', function (Blueprint $table) {
                $table->dropIndex('idx_sessions_user');
                $table->dropIndex('idx_sessions_activity');
            });
        }
        
        // cache テーブルのインデックス削除
        if (Schema::hasTable('cache')) {
            Schema::table('cache', function (Blueprint $table) {
                if (Schema::hasIndex('cache', 'cache_key_unique')) {
                    $table->dropIndex('cache_key_unique');
                }
                $table->dropIndex('idx_cache_expiration');
            });
        }
        
        // jobs テーブルのインデックス削除
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                $table->dropIndex('idx_jobs_queue_reserved');
                $table->dropIndex('idx_jobs_available');
            });
        }
        
        // prefectures テーブルのインデックス削除
        if (Schema::hasTable('prefectures')) {
            Schema::table('prefectures', function (Blueprint $table) {
                $table->dropIndex('idx_prefectures_region');
                $table->dropIndex('idx_prefectures_code');
                $table->dropIndex('idx_prefectures_name');
            });
        }
        
        // event_registrations テーブルのインデックス削除
        if (Schema::hasTable('event_registrations')) {
            Schema::table('event_registrations', function (Blueprint $table) {
                $table->dropIndex('idx_registrations_event_status');
                $table->dropIndex('idx_registrations_customer_event');
                $table->dropIndex('idx_registrations_created');
            });
        }
        
        // event_participants テーブルのインデックス削除
        if (Schema::hasTable('event_participants')) {
            Schema::table('event_participants', function (Blueprint $table) {
                $table->dropIndex('idx_participants_event_status');
                $table->dropIndex('idx_participants_checkin');
            });
        }
        
        // staff_schedules テーブルのインデックス削除
        if (Schema::hasTable('staff_schedules')) {
            Schema::table('staff_schedules', function (Blueprint $table) {
                $table->dropIndex('idx_schedules_event_staff');
                $table->dropIndex('idx_schedules_date_start');
            });
        }
    }
};