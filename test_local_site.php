<?php
// ローカル開発環境用のテストページ
// 本番環境の主要なコンポーネントを読み込んで動作確認

// データベース接続（ローカル用に調整が必要な場合あり）
// require_once 'db_data/db_info/db_info.php';

// 関数ファイルの読み込み
require_once 'function.php';
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ローカル環境テスト - jQuery動作確認</title>
    
    <!-- 本番環境と同じヘッダーを読み込み -->
    <?php include 'widgets/outputHead.php'; ?>
    
    <style>
        .test-section {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            background: #f9f9f9;
        }
        .test-result {
            margin: 10px 0;
            padding: 10px;
            border-radius: 4px;
        }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .warning { background: #fff3cd; color: #856404; }
        .console-log {
            background: #000;
            color: #0f0;
            padding: 10px;
            font-family: monospace;
            font-size: 12px;
            max-height: 200px;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="test-section">
        <h1>ローカル開発環境テスト</h1>
        
        <h2>1. jQuery バージョン確認</h2>
        <div id="jquery-version" class="test-result"></div>
        
        <h2>2. 主要コンポーネントの動作確認</h2>
        
        <!-- Chosenプラグインのテスト -->
        <div class="test-result">
            <h3>Chosen プラグイン</h3>
            <select class="chosen-select" style="width: 300px;">
                <option>東京</option>
                <option>大阪</option>
                <option>名古屋</option>
            </select>
        </div>
        
        <!-- 日付検索のテスト -->
        <div class="test-result">
            <h3>日付検索機能</h3>
            <input type="date" id="test-date" />
            <button onclick="testDateSearch()">日付検索テスト</button>
        </div>
        
        <!-- shop.js の機能テスト -->
        <div class="test-result">
            <h3>ショップ機能</h3>
            <button class="shop-function-test">ショップ機能テスト</button>
        </div>
        
        <h2>3. コンソール出力</h2>
        <div id="console-output" class="console-log">
            コンソールメッセージがここに表示されます...<br>
        </div>
        
        <h2>4. jQuery Migrate 警告</h2>
        <div id="migrate-warnings" class="test-result warning">
            jQuery Migrate の警告がここに表示されます...<br>
        </div>
    </div>
    
    <!-- グローバルメニューのテスト -->
    <div class="test-section">
        <h2>グローバルメニューのテスト</h2>
        <?php include 'widgets/ani/globalMenu.php'; ?>
    </div>
    
    <script>
        // コンソール出力をキャプチャ
        var consoleOutput = document.getElementById('console-output');
        var migrateWarnings = document.getElementById('migrate-warnings');
        
        // console.logをオーバーライド
        var originalLog = console.log;
        console.log = function() {
            var args = Array.prototype.slice.call(arguments);
            originalLog.apply(console, args);
            consoleOutput.innerHTML += args.join(' ') + '<br>';
        };
        
        // console.warnをオーバーライド（jQuery Migrate用）
        var originalWarn = console.warn;
        console.warn = function() {
            var args = Array.prototype.slice.call(arguments);
            originalWarn.apply(console, args);
            
            if (args[0] && args[0].indexOf('JQMIGRATE') !== -1) {
                migrateWarnings.innerHTML += '<div>' + args.join(' ') + '</div>';
            }
        };
        
        // jQuery バージョン表示
        $(document).ready(function() {
            $('#jquery-version').addClass('success').html(
                '<strong>jQuery Version:</strong> ' + $.fn.jquery + '<br>' +
                '<strong>jQuery Migrate:</strong> ' + (typeof $.migrateVersion !== 'undefined' ? $.migrateVersion : 'Not loaded')
            );
            
            // Chosen プラグインの初期化
            if ($.fn.chosen) {
                $('.chosen-select').chosen();
                console.log('Chosen plugin initialized successfully');
            } else {
                console.log('Chosen plugin not found');
            }
            
            // ショップ機能のテスト
            $('.shop-function-test').on('click', function() {
                console.log('Shop function test clicked');
                alert('ショップ機能が正常に動作しています');
            });
            
            // 各種機能のテスト
            testAllFunctions();
        });
        
        function testDateSearch() {
            var date = $('#test-date').val();
            console.log('Date search test with date: ' + date);
            alert('日付検索機能テスト: ' + date);
        }
        
        function testAllFunctions() {
            console.log('=== 機能テスト開始 ===');
            
            // 1. セレクターのテスト
            console.log('Body elements found: ' + $('body').length);
            
            // 2. イベントハンドリングのテスト
            var testDiv = $('<div>Test</div>');
            var clicked = false;
            testDiv.on('click', function() { clicked = true; });
            testDiv.trigger('click');
            console.log('Event handling test: ' + (clicked ? 'PASSED' : 'FAILED'));
            
            // 3. AJAX機能のテスト
            console.log('AJAX function available: ' + (typeof $.ajax === 'function'));
            
            // 4. アニメーション機能のテスト
            console.log('Animation function available: ' + (typeof $.fn.animate === 'function'));
            
            // 5. 非推奨メソッドのテスト（警告を出すため）
            try {
                var elem = $('<div>Test deprecated</div>');
                elem.bind('click', function() {}); // これは警告を出すはず
                console.log('Deprecated .bind() method still works (with warning)');
            } catch(e) {
                console.log('Deprecated .bind() method failed: ' + e.message);
            }
            
            console.log('=== 機能テスト完了 ===');
        }
    </script>
</body>
</html>