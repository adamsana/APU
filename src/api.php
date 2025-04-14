<?php
require_once __DIR__.'/logic.php';

// Endpoint: /api/cache-info
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/api/cache-info') !== false) {
    global $redisCache;
    
    $cacheStatus = [
        'redis_enabled' => $redisCache->isEnabled(),
        'file_cache_exists' => file_exists(CACHE_FILE),
        'file_cache_age' => file_exists(CACHE_FILE) ? (time() - filemtime(CACHE_FILE)) : null,
        'cache_expire' => CACHE_EXPIRE
    ];
    
    echo json_encode([
        'msg' => 'Cache Information',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200,
        'result' => $cacheStatus
    ]);
    exit;
}
?>
    $jsonStructure = [
        'list' => [
            'msg' => 'OK',
            'server_time' => 'string',
            'status' => 200,
            'result' => [
                'total_pages' => 'integer',
                'files' => [[
                    "single_img" => "string",
                    "protected_embed" => "string",
                    "splash_img" => "string",
                    "views" => "integer",
                    "last_view" => "integer",
                    "filecode" => "string",
                    "canplay" => "boolean",
                    "title" => "string",
                    "status" => 200,
                    "size" => "integer",
                    "protected_dl" => "string",
                    "uploaded" => "string",
                    "length" => "integer",
                    "file_code" => "string"
                ]],
                'results_total' => 'integer',
                'results' => 'integer'
            ]
        ],
        'info' => [
            'msg' => 'OK',
            'server_time' => 'string',
            'status' => 200,
            'result' => [
                "single_img" => "string",
                "protected_embed" => "string",
                "splash_img" => "string",
                "views" => "integer",
                "last_view" => "integer",
                "filecode" => "string",
                "canplay" => "boolean",
                "title" => "string",
                "status" => 200,
                "size" => "integer",
                "protected_dl" => "string",
                "uploaded" => "string",
                "length" => "integer",
                "file_code" => "string"
            ]
        ],
        'search' => [
            'msg' => 'OK',
            'result' => [[
                "single_img" => "string",
                "protected_embed" => "string",
                "splash_img" => "string",
                "views" => "integer",
                "last_view" => "integer",
                "filecode" => "string",
                "canplay" => "boolean",
                "title" => "string",
                "status" => 200,
                "size" => "integer",
                "protected_dl" => "string",
                "uploaded" => "string",
                "length" => "integer",
                "file_code" => "string"
            ]],
            'status' => 200,
            'server_time' => 'string',
            'pagination' => [
                'page' => 'integer',
                'per_page' => 'integer',
                'total_results' => 'integer',
                'total_pages' => 'integer'
            ]
        ]
    ];

    echo json_encode([
        'msg' => 'API Documentation',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200,
        'endpoints' => $endpoints,
        'json_structure' => $jsonStructure
    ], JSON_PRETTY_PRINT);
    exit;
}

// Endpoint: /api/list
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/api/list') !== false) {
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 100;
    
    $result = getPaginatedData($page, $perPage);
    $mappedData = array_map('formatApiItem', $result['items']);
    
    echo json_encode([
        'msg' => 'OK',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200,
        'result' => [
            'total_pages' => $result['pagination']['total_pages'],
            'files' => $mappedData,
            'results_total' => $result['pagination']['total_items'],
            'results' => count($result['items'])
        ]
    ]);
    exit;
}

// Endpoint: /api/info
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/api/info') !== false) {
    if (!isset($_GET['file_code']) || empty($_GET['file_code'])) {
        http_response_code(400);
        echo json_encode([
            'msg' => 'Bad Request',
            'server_time' => date('Y-m-d H:i:s'),
            'status' => 400,
            'result' => 'Parameter file_code diperlukan'
        ]);
        exit;
    }

    $item = findItemByFileCode($_GET['file_code']);
    
    if (!$item) {
        http_response_code(404);
        echo json_encode([
            'msg' => 'Not Found',
            'server_time' => date('Y-m-d H:i:s'),
            'status' => 404,
            'result' => 'Data tidak ditemukan'
        ]);
        exit;
    }

    echo json_encode([
        'msg' => 'OK',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200,
        'result' => formatApiItem($item)
    ]);
    exit;
}

// Endpoint: /api/search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && strpos($_SERVER['REQUEST_URI'], '/api/search') !== false) {
    if (empty($_GET['q'])) {
        http_response_code(400);
        echo json_encode([
            'msg' => 'Bad Request',
            'server_time' => date('Y-m-d H:i:s'),
            'status' => 400,
            'result' => 'Parameter q diperlukan'
        ]);
        exit;
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $perPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : 100;
    
    $result = searchData($_GET['q'], $page, $perPage);
    $mappedData = array_map('formatApiItem', $result['data']);
    
    echo json_encode([
        'msg' => 'OK',
        'server_time' => date('Y-m-d H:i:s'),
        'status' => 200,
        'result' => $mappedData,
        'pagination' => [
            'page' => $page,
            'per_page' => $perPage,
            'total_results' => $result['total_results'],
            'total_pages' => $result['total_pages']
        ]
    ]);
    exit;
}

// Format item untuk response API
function formatApiItem($item) {
    return [
        "single_img" => $item['single_img'],
        "protected_embed" => $item['protected_embed'],
        "splash_img" => $item['splash_img'],
        "views" => $item['views'],
        "last_view" => $item['last_view'],
        "filecode" => $item['filecode'],
        "canplay" => $item['canplay'],
        "title" => $item['title'],
        "status" => 200,
        "size" => $item['size'],
        "protected_dl" => $item['protected_dl'],
        "uploaded" => $item['uploaded'],
        "length" => $item['length'],
        "file_code" => $item['file_code']
    ];
}

// Jika endpoint tidak ditemukan
http_response_code(404);
echo json_encode([
    'msg' => 'Not Found',
    'server_time' => date('Y-m-d H:i:s'),
    'status' => 404,
    'result' => 'Endpoint tidak ditemukan'
]);
?>