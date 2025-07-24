<?php
// ローカル開発環境用の設定
define('IS_LOCAL', true);

// ローカル環境のベースパス
define('LOCAL_BASE_PATH', __DIR__);

// 本番環境のパスをローカル用に変換する関数
function getLocalPath($productionPath) {
    if (IS_LOCAL) {
        // 本番環境のパスをローカルパスに変換
        $localPath = str_replace(
            '/home/users/1/lolipop.jp-30251d4519441da4/web/ikoiko/',
            LOCAL_BASE_PATH . '/',
            $productionPath
        );
        return $localPath;
    }
    return $productionPath;
}

// データベース設定（ローカル用）
if (IS_LOCAL) {
    define('DB_HOST', 'localhost');
    define('DB_NAME', 'ikoiko');
    define('DB_USER', 'root');
    define('DB_PASS', '');
}
?>