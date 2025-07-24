<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>jQuery動作確認（データベース接続なし）</title>
    
    <!-- CSSファイル -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/content.css">
    <link rel="stylesheet" href="css/globalMenu.css">
    <link rel="stylesheet" href="css/chosen.min.css">
    
    <!-- jQuery -->
    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/jquery-migrate-3.4.1.min.js"></script>
    <script src="js/chosen.jquery.min.js"></script>
    <script src="js/shop.js"></script>
    <script src="js/dateSearch.js"></script>
    
    <style>
        body {
            font-family: 'Hiragino Sans', 'Meiryo', sans-serif;
        }
        .dev-banner {
            background: #fffacd;
            padding: 10px;
            text-align: center;
            border-bottom: 2px solid #ffd700;
            font-weight: bold;
        }
        .test-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .test-section {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 20px;
            margin: 20px 0;
        }
        .test-result {
            margin: 10px 0;
            padding: 10px;
            border-radius: 3px;
        }
        .success { background: #d4edda; color: #155724; }
        .warning { background: #fff3cd; color: #856404; }
        .error { background: #f8d7da; color: #721c24; }
        .console-log {
            background: #000;
            color: #0f0;
            padding: 10px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-height: 300px;
            overflow-y: auto;
            margin: 10px 0;
        }
        .animate-box {
            width: 100px;
            height: 100px;
            background: #007bff;
            margin: 10px 0;
            position: relative;
        }
    </style>
</head>
<body>
    <div class="dev-banner">
        ローカル開発環境 - jQuery動作確認専用ページ（データベース接続なし）
    </div>
    
    <div class="test-container">
        <h1>jQuery 3.7.1 動作確認</h1>
        
        <!-- バージョン情報 -->
        <div class="test-section">
            <h2>1. バージョン情報</h2>
            <div id="version-info" class="test-result"></div>
        </div>
        
        <!-- 基本機能テスト -->
        <div class="test-section">
            <h2>2. 基本機能テスト</h2>
            <div id="basic-tests"></div>
        </div>
        
        <!-- プラグインテスト -->
        <div class="test-section">
            <h2>3. プラグインテスト</h2>
            
            <h3>Chosen セレクトボックス</h3>
            <select class="chosen-select" style="width: 350px;" data-placeholder="都市を選択してください">
                <option value=""></option>
                <option value="tokyo">東京</option>
                <option value="osaka">大阪</option>
                <option value="nagoya">名古屋</option>
                <option value="fukuoka">福岡</option>
                <option value="sapporo">札幌</option>
                <option value="sendai">仙台</option>
                <option value="hiroshima">広島</option>
            </select>
            
            <h3>複数選択 Chosen</h3>
            <select class="chosen-select" multiple style="width: 350px;" data-placeholder="複数の都市を選択">
                <option value="tokyo">東京</option>
                <option value="osaka">大阪</option>
                <option value="nagoya">名古屋</option>
                <option value="fukuoka">福岡</option>
                <option value="sapporo">札幌</option>
            </select>
        </div>
        
        <!-- アニメーションテスト -->
        <div class="test-section">
            <h2>4. アニメーションテスト</h2>
            <button id="animate-btn" class="btn btn-primary">アニメーション実行</button>
            <button id="fade-btn" class="btn btn-secondary">フェードイン/アウト</button>
            <button id="slide-btn" class="btn btn-info">スライドアップ/ダウン</button>
            <div id="animate-box" class="animate-box"></div>
        </div>
        
        <!-- イベントハンドリングテスト -->
        <div class="test-section">
            <h2>5. イベントハンドリングテスト</h2>
            <button id="test-click">クリックテスト</button>
            <input type="text" id="test-input" placeholder="キーアップイベントテスト">
            <div id="event-result"></div>
        </div>
        
        <!-- コンソール出力 -->
        <div class="test-section">
            <h2>6. コンソール出力</h2>
            <div id="console-output" class="console-log">
                コンソールメッセージがここに表示されます...<br>
            </div>
        </div>
        
        <!-- jQuery Migrate 警告 -->
        <div class="test-section">
            <h2>7. jQuery Migrate 警告</h2>
            <button id="test-deprecated">非推奨メソッドテスト</button>
            <div id="migrate-warnings" class="test-result warning" style="display: none;">
                警告メッセージがここに表示されます...<br>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function() {
            var consoleOutput = $('#console-output');
            var migrateWarnings = $('#migrate-warnings');
            
            // コンソールオーバーライド
            var originalLog = console.log;
            var originalWarn = console.warn;
            
            console.log = function() {
                var args = Array.prototype.slice.call(arguments);
                originalLog.apply(console, args);
                consoleOutput.append('<div style="color: #0f0;">[LOG] ' + args.join(' ') + '</div>');
                consoleOutput.scrollTop(consoleOutput[0].scrollHeight);
            };
            
            console.warn = function() {
                var args = Array.prototype.slice.call(arguments);
                originalWarn.apply(console, args);
                
                var message = args.join(' ');
                if (message.indexOf('JQMIGRATE') !== -1) {
                    migrateWarnings.show().append('<div>' + message + '</div>');
                    consoleOutput.append('<div style="color: #ff0;">[WARN] ' + message + '</div>');
                } else {
                    consoleOutput.append('<div style="color: #ff0;">[WARN] ' + message + '</div>');
                }
                consoleOutput.scrollTop(consoleOutput[0].scrollHeight);
            };
            
            // 1. バージョン情報
            $('#version-info').addClass('success').html(
                '<strong>jQuery Version:</strong> ' + $.fn.jquery + '<br>' +
                '<strong>jQuery Migrate:</strong> ' + (typeof $.migrateVersion !== 'undefined' ? $.migrateVersion : 'Not loaded') + '<br>' +
                '<strong>User Agent:</strong> ' + navigator.userAgent
            );
            
            console.log('jQuery ' + $.fn.jquery + ' loaded successfully');
            
            // 2. 基本機能テスト
            var basicTests = $('#basic-tests');
            
            // セレクターテスト
            var selectorTest = $('body').length === 1;
            basicTests.append('<div class="test-result ' + (selectorTest ? 'success' : 'error') + '">セレクターテスト: ' + (selectorTest ? 'OK' : 'NG') + '</div>');
            
            // DOM操作テスト
            var testDiv = $('<div>テスト要素</div>');
            var domTest = testDiv.text() === 'テスト要素';
            basicTests.append('<div class="test-result ' + (domTest ? 'success' : 'error') + '">DOM操作テスト: ' + (domTest ? 'OK' : 'NG') + '</div>');
            
            // イベントテスト
            var eventTest = false;
            var testBtn = $('<button>Test</button>');
            testBtn.on('click', function() { eventTest = true; });
            testBtn.trigger('click');
            basicTests.append('<div class="test-result ' + (eventTest ? 'success' : 'error') + '">イベントテスト: ' + (eventTest ? 'OK' : 'NG') + '</div>');
            
            // AJAXテスト
            var ajaxTest = typeof $.ajax === 'function';
            basicTests.append('<div class="test-result ' + (ajaxTest ? 'success' : 'error') + '">AJAX機能: ' + (ajaxTest ? '利用可能' : '利用不可') + '</div>');
            
            // 3. Chosenプラグイン初期化
            if ($.fn.chosen) {
                $('.chosen-select').chosen({
                    no_results_text: "該当する項目がありません: ",
                    width: "350px"
                });
                console.log('Chosen plugin initialized successfully');
            } else {
                console.log('ERROR: Chosen plugin not found');
            }
            
            // 4. アニメーションテスト
            $('#animate-btn').on('click', function() {
                console.log('Animation test started');
                $('#animate-box').animate({
                    width: '200px',
                    height: '200px',
                    left: '100px'
                }, 500).animate({
                    width: '100px',
                    height: '100px',
                    left: '0px'
                }, 500);
            });
            
            $('#fade-btn').on('click', function() {
                $('#animate-box').fadeOut(300).fadeIn(300);
            });
            
            $('#slide-btn').on('click', function() {
                $('#animate-box').slideUp(300).slideDown(300);
            });
            
            // 5. イベントハンドリングテスト
            var clickCount = 0;
            $('#test-click').on('click', function() {
                clickCount++;
                $('#event-result').html('クリック回数: ' + clickCount);
                console.log('Button clicked: ' + clickCount);
            });
            
            $('#test-input').on('keyup', function() {
                var value = $(this).val();
                $('#event-result').html('入力値: ' + value);
            });
            
            // 7. 非推奨メソッドテスト
            $('#test-deprecated').on('click', function() {
                console.log('Testing deprecated methods...');
                
                try {
                    // .bind() - jQuery 3.0で削除
                    var elem1 = $('<div>Test bind</div>');
                    elem1.bind('click', function() {});
                    console.log('.bind() method tested');
                    
                    // .unbind() - jQuery 3.0で削除
                    elem1.unbind('click');
                    console.log('.unbind() method tested');
                    
                    // .live() - jQuery 1.9で削除
                    try {
                        $(document).live('click', function() {});
                    } catch(e) {
                        console.log('.live() method is completely removed');
                    }
                    
                    // .die() - jQuery 1.9で削除
                    try {
                        $(document).die('click');
                    } catch(e) {
                        console.log('.die() method is completely removed');
                    }
                    
                } catch(e) {
                    console.log('Error testing deprecated methods: ' + e.message);
                }
            });
            
            // 初期ログ
            console.log('=== jQuery Test Page Loaded ===');
            console.log('All tests completed');
        });
    </script>
</body>
</html>