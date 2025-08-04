<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class OptimizeDatabase extends Command
{
    /**
     * コマンドのシグネチャ
     */
    protected $signature = 'db:optimize {--analyze : インデックスの使用状況を分析}';

    /**
     * コマンドの説明
     */
    protected $description = 'データベースのパフォーマンスを最適化';

    /**
     * コマンドの実行
     */
    public function handle()
    {
        $this->info('データベース最適化を開始します...');
        
        if ($this->option('analyze')) {
            $this->analyzeIndexUsage();
        } else {
            $this->optimizeTables();
        }
        
        $this->info('データベース最適化が完了しました。');
        
        return Command::SUCCESS;
    }
    
    /**
     * テーブルの最適化
     */
    protected function optimizeTables()
    {
        $tables = [
            'events',
            'areas',
            'customers',
            'users',
            'content',
            'prefectures'
        ];
        
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            
            $this->info("テーブル '{$table}' を最適化中...");
            
            // SQLiteの場合
            if (config('database.default') === 'sqlite') {
                DB::statement("VACUUM");
                DB::statement("ANALYZE {$table}");
            }
            // MySQLの場合
            elseif (config('database.default') === 'mysql') {
                DB::statement("OPTIMIZE TABLE {$table}");
                DB::statement("ANALYZE TABLE {$table}");
            }
            // PostgreSQLの場合
            elseif (config('database.default') === 'pgsql') {
                DB::statement("VACUUM ANALYZE {$table}");
            }
        }
        
        // キャッシュをクリア
        $this->call('cache:clear');
        $this->call('config:cache');
        $this->call('route:cache');
        
        $this->info('テーブル最適化が完了しました。');
    }
    
    /**
     * インデックスの使用状況を分析
     */
    protected function analyzeIndexUsage()
    {
        $this->info('インデックス使用状況を分析中...');
        
        // よく使われるクエリパターン
        $queries = [
            [
                'description' => 'イベント検索（タイプ・ステータス・日付）',
                'query' => 'SELECT * FROM events WHERE event_type = ? AND status = ? AND event_date >= ?',
                'params' => ['anime', 'active', now()->toDateString()]
            ],
            [
                'description' => 'エリア別イベント検索',
                'query' => 'SELECT * FROM events WHERE area_id = ? AND event_date >= ? ORDER BY event_date',
                'params' => [1, now()->toDateString()]
            ],
            [
                'description' => '顧客検索（イベント・ステータス）',
                'query' => 'SELECT * FROM customers WHERE event_id = ? AND status = ?',
                'params' => [1, 'confirmed']
            ],
            [
                'description' => 'エリア検索（都道府県・アクティブ）',
                'query' => 'SELECT * FROM areas WHERE prefecture_id = ? AND is_active = ?',
                'params' => [13, true]
            ]
        ];
        
        $results = [];
        
        foreach ($queries as $queryInfo) {
            $startTime = microtime(true);
            
            try {
                DB::select($queryInfo['query'], $queryInfo['params']);
                $executionTime = (microtime(true) - $startTime) * 1000;
                
                $results[] = [
                    'description' => $queryInfo['description'],
                    'execution_time' => round($executionTime, 2) . ' ms',
                    'status' => $executionTime < 10 ? '✓ 高速' : ($executionTime < 50 ? '△ 普通' : '✗ 遅い')
                ];
            } catch (\Exception $e) {
                $results[] = [
                    'description' => $queryInfo['description'],
                    'execution_time' => 'N/A',
                    'status' => '✗ エラー'
                ];
            }
        }
        
        // 結果を表示
        $this->table(
            ['クエリ', '実行時間', 'ステータス'],
            $results
        );
        
        // インデックス情報を表示
        $this->displayIndexInfo();
    }
    
    /**
     * インデックス情報の表示
     */
    protected function displayIndexInfo()
    {
        $this->info("\n現在のインデックス情報:");
        
        $tables = ['events', 'areas', 'customers', 'users'];
        
        foreach ($tables as $table) {
            if (!Schema::hasTable($table)) {
                continue;
            }
            
            $this->info("\n{$table} テーブル:");
            
            // SQLiteの場合
            if (config('database.default') === 'sqlite') {
                $indexes = DB::select("PRAGMA index_list({$table})");
                foreach ($indexes as $index) {
                    $columns = DB::select("PRAGMA index_info({$index->name})");
                    $columnNames = array_map(fn($col) => $col->name, $columns);
                    $this->line("  - {$index->name}: " . implode(', ', $columnNames));
                }
            }
            // MySQLの場合
            elseif (config('database.default') === 'mysql') {
                $indexes = DB::select("SHOW INDEX FROM {$table}");
                $groupedIndexes = [];
                foreach ($indexes as $index) {
                    $groupedIndexes[$index->Key_name][] = $index->Column_name;
                }
                foreach ($groupedIndexes as $indexName => $columns) {
                    $this->line("  - {$indexName}: " . implode(', ', $columns));
                }
            }
        }
    }
}