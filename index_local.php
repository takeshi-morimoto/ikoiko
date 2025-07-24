<?php 
// ローカル環境用の設定を読み込み
require_once 'config.local.php';

// DBの初期化（ローカル用パス）
$dbInitPath = getLocalPath("/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/db_data/db_init.php");
if (file_exists($dbInitPath)) {
    require($dbInitPath);
    $db->query("SET NAMES utf8");
} else {
    // データベース接続なしでも表示確認できるようにする
    $db = null;
}

// 以下は元のindex.phpの内容をコピー
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ローカル環境テスト - イコイコ</title>
    <?php include 'widgets/outputHead.php'; ?>
</head>
<body>
    <div style="background: #fffacd; padding: 10px; text-align: center; border-bottom: 2px solid #ffd700;">
        <strong>ローカル開発環境で実行中</strong>
    </div>
    
    <!-- ヘッダー -->
    <?php include 'widgets/ani/globalMenu.php'; ?>
    
    <!-- メインコンテンツ -->
    <div class="container" style="margin-top: 20px;">
        <h1>jQuery動作確認</h1>
        
        <div class="test-section" style="border: 1px solid #ddd; padding: 20px; margin: 20px 0;">
            <h2>動作テスト</h2>
            
            <p>jQuery Version: <span id="jquery-version"></span></p>
            <p>jQuery Migrate: <span id="migrate-version"></span></p>
            
            <h3>Chosenプラグインテスト</h3>
            <select class="chosen-select" style="width: 300px;">
                <option>東京</option>
                <option>大阪</option>
                <option>名古屋</option>
                <option>福岡</option>
                <option>札幌</option>
            </select>
            
            <h3>アニメーションテスト</h3>
            <button id="anim-test">アニメーションテスト</button>
            <div id="anim-box" style="width: 100px; height: 100px; background: #007bff; margin: 10px 0;"></div>
            
            <h3>コンソールログ</h3>
            <div id="console-log" style="background: #f8f9fa; border: 1px solid #dee2e6; padding: 10px; font-family: monospace; font-size: 12px; max-height: 200px; overflow-y: auto;">
                コンソールメッセージがここに表示されます...<br>
            </div>
        </div>
    </div>
    
    <!-- フッター -->
    <?php include 'widgets/footer.php'; ?>
    
    <script>
        $(document).ready(function() {
            // jQueryバージョン表示
            $('#jquery-version').text($.fn.jquery);
            $('#migrate-version').text(typeof $.migrateVersion !== 'undefined' ? $.migrateVersion : 'Not loaded');
            
            // コンソールログキャプチャ
            var consoleDiv = $('#console-log');
            var originalLog = console.log;
            var originalWarn = console.warn;
            
            console.log = function() {
                var args = Array.prototype.slice.call(arguments);
                originalLog.apply(console, args);
                consoleDiv.append('<div style="color: #333;">' + args.join(' ') + '</div>');
                consoleDiv.scrollTop(consoleDiv[0].scrollHeight);
            };
            
            console.warn = function() {
                var args = Array.prototype.slice.call(arguments);
                originalWarn.apply(console, args);
                
                var message = args.join(' ');
                if (message.indexOf('JQMIGRATE') !== -1) {
                    consoleDiv.append('<div style="color: #856404; background: #fff3cd; padding: 2px;">' + message + '</div>');
                } else {
                    consoleDiv.append('<div style="color: #856404;">' + message + '</div>');
                }
                consoleDiv.scrollTop(consoleDiv[0].scrollHeight);
            };
            
            console.log('jQuery ' + $.fn.jquery + ' loaded successfully');
            
            // Chosenプラグイン初期化
            if ($.fn.chosen) {
                $('.chosen-select').chosen();
                console.log('Chosen plugin initialized');
            } else {
                console.log('Chosen plugin not found');
            }
            
            // アニメーションテスト
            $('#anim-test').on('click', function() {
                console.log('Animation test started');
                $('#anim-box').animate({
                    width: '200px',
                    height: '200px',
                    opacity: 0.5
                }, 500).animate({
                    width: '100px',
                    height: '100px',
                    opacity: 1
                }, 500);
            });
            
            // 非推奨メソッドテスト（警告を出すため）
            try {
                var testElem = $('<div>Test</div>');
                testElem.bind('click', function() {});
                console.log('Deprecated .bind() method test executed (should show warning)');
            } catch(e) {
                console.log('Error with deprecated method: ' + e.message);
            }
            
            // その他の機能テスト
            console.log('Event handling: ' + (typeof $.fn.on === 'function' ? 'OK' : 'NG'));
            console.log('AJAX support: ' + (typeof $.ajax === 'function' ? 'OK' : 'NG'));
            console.log('Animation support: ' + (typeof $.fn.animate === 'function' ? 'OK' : 'NG'));
        });
    </script>
</body>
</html>