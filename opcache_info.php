<?php
// OPcacheの状態を確認
if (!function_exists('opcache_get_status')) {
    die('OPcache is not installed or enabled');
}

$status = opcache_get_status();
$config = opcache_get_configuration();

echo "<h1>OPcache Status</h1>";
echo "<pre>";
echo "OPcache Enabled: " . ($status ? 'Yes' : 'No') . "\n";
if ($status) {
    echo "Memory Usage: " . round($status['memory_usage']['used_memory'] / 1024 / 1024, 2) . " MB / " . 
         round(($status['memory_usage']['used_memory'] + $status['memory_usage']['free_memory']) / 1024 / 1024, 2) . " MB\n";
    echo "Hit Rate: " . round($status['opcache_statistics']['opcache_hit_rate'], 2) . "%\n";
    echo "Cached Scripts: " . $status['opcache_statistics']['num_cached_scripts'] . "\n";
}
echo "</pre>";

echo "<h2>Configuration</h2>";
echo "<pre>";
print_r($config['directives']);
echo "</pre>";

// セキュリティのため、本番環境では削除してください
if (isset($_GET['reset']) && $_GET['reset'] === '1') {
    opcache_reset();
    echo "<p>OPcache has been reset!</p>";
}
?>