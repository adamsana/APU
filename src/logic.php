<?php
header('Content-Type: application/json');

// Endpoint: /api/cache-info
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/api/cache-info') !== false) {
    $cacheInfo = [
        'redis_enabled' => REDIS_ENABLED,
        'local_file_exists' => file_exists(DATA_FILE),
        'local_file_size' => file_exists(DATA_FILE) ? filesize(DATA_FILE) : 0,
        'local_file_modified' => file_exists(DATA_FILE) ? date('Y-m-d H:i:s', filemtime(DATA_FILE)) : null,
        'cache_expire' => CACHE_EXPIRE
    ];
    
    echo json_encode([
        'msg' => 'Cache Information',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200,
        'result' => $cacheInfo
    ]);
    exit;
}

// Endpoint: /api/refresh-cache
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/api/refresh-cache') !== false) {
    refreshCache();
    echo json_encode([
        'msg' => 'Cache refreshed successfully',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200
    ]);
    exit;
}

// [Endpoint lainnya tetap sama dengan sebelumnya...]
?>