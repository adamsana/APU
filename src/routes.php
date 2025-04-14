<?php
require_once __DIR__.'/logic.php';

// Handle refresh cache
if (isset($_GET['refresh_cache'])) {
    refreshCache();
    header("Location: ".str_replace('?refresh_cache=1', '', $_SERVER['REQUEST_URI']));
    exit;
}

// Routing
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request = trim(str_replace(dirname($_SERVER['SCRIPT_NAME']), '', $request), '/');

// API Routes
if (strpos($request, 'api/') === 0) {
    require_once __DIR__.'/api.php';
    exit;
}

// Web Routes
switch ($request) {
    case 'detail':
        require_once __DIR__.'/../views/detail_view.php';
        break;
    case '':
    case 'list':
    default:
        require_once __DIR__.'/../views/list_view.php';
        break;
}
?>