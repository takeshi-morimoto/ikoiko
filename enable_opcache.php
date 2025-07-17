<?php
// OPcacheを動的に有効化する試み
ini_set('opcache.enable', '1');
ini_set('opcache.enable_cli', '0');

// 設定確認
echo "<h1>OPcache有効化テスト</h1>";
echo "<pre>";
echo "opcache.enable: " . ini_get('opcache.enable') . "\n";
echo "OPcache拡張: " . (extension_loaded('Zend OPcache') ? 'インストール済み' : '未インストール') . "\n";
echo "</pre>";

// ロリポップサーバー情報
echo "<h2>サーバー情報</h2>";
echo "<pre>";
echo "Server API: " . php_sapi_name() . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "設定ファイル: " . php_ini_loaded_file() . "\n";
echo "追加設定ファイル: " . php_ini_scanned_files() . "\n";
echo "</pre>";

echo "<h2>対応方法</h2>";
echo "<p>ロリポップサーバーでOPcacheを有効化するには：</p>";
echo "<ol>";
echo "<li>ロリポップ管理画面にログイン</li>";
echo "<li>「サーバーの管理・設定」→「PHP設定」</li>";
echo "<li>該当ドメインの「設定変更」をクリック</li>";
echo "<li>「PHPアクセラレータ」または「OPcache」のオプションを有効化</li>";
echo "</ol>";
?>