<?php
define('BASE_PATH', dirname(__DIR__));
define('CACHE_DIR', BASE_PATH . '/cache');
define('DATA_DIR', BASE_PATH . '/data');
define('DATA_FILE', DATA_DIR . '/data.json');
define('CACHE_EXPIRE', 3600); // 1 jam dalam detik
define('API_BASE_URL', 'https://api.semol.site/api/list');

// Redis Configuration
define('REDIS_ENABLED', extension_loaded('redis'));
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_PREFIX', 'semol_api:');
?>