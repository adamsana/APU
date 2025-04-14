<?php
require_once __DIR__.'/config.php';

class DataManager {
    private $redis;
    
    public function __construct() {
        if (REDIS_ENABLED) {
            $this->redis = new Redis();
            try {
                $this->redis->connect(REDIS_HOST, REDIS_PORT);
            } catch (Exception $e) {
                error_log("Redis connection failed: " . $e->getMessage());
            }
        }
        
        // Ensure directories exist
        if (!file_exists(DATA_DIR)) {
            mkdir(DATA_DIR, 0755, true);
        }
        if (!file_exists(CACHE_DIR)) {
            mkdir(CACHE_DIR, 0755, true);
        }
    }

    public function getData() {
        // Try Redis first
        if (REDIS_ENABLED && $this->redis) {
            $data = $this->redis->get(REDIS_PREFIX . 'api_data');
            if ($data !== false) {
                return json_decode($data, true);
            }
        }

        // Try Local File
        if (file_exists(DATA_FILE)) {
            $data = file_get_contents(DATA_FILE);
            $decoded = json_decode($data, true);
            if ($decoded) {
                // Store to Redis if available
                if (REDIS_ENABLED && $this->redis) {
                    $this->redis->setex(
                        REDIS_PREFIX . 'api_data', 
                        CACHE_EXPIRE, 
                        $data
                    );
                }
                return $decoded;
            }
        }

        // Fetch from API
        $apiData = $this->fetchFromApi();
        if ($apiData) {
            // Save to local file
            file_put_contents(DATA_FILE, json_encode($apiData));
            
            // Store to Redis if available
            if (REDIS_ENABLED && $this->redis) {
                $this->redis->setex(
                    REDIS_PREFIX . 'api_data', 
                    CACHE_EXPIRE, 
                    json_encode($apiData)
                );
            }
            
            return $apiData;
        }

        return null;
    }

    private function fetchFromApi() {
        $allData = [];
        $page = 1;
        
        do {
            $url = API_BASE_URL . '?page=' . $page;
            $response = file_get_contents($url);
            $data = json_decode($response, true);
            
            if (!$data || !isset($data['result']['files'])) {
                break;
            }
            
            $allData = array_merge($allData, $data['result']['files']);
            
            // Check if there are more pages
            if (isset($data['result']['total_pages'])) {
                $totalPages = $data['result']['total_pages'];
            } else {
                $totalPages = $page; // Assume no more pages if not specified
            }
            
            $page++;
        } while ($page <= $totalPages);
        
        return $allData;
    }

    public function clearCache() {
        // Clear Redis
        if (REDIS_ENABLED && $this->redis) {
            $this->redis->del(REDIS_PREFIX . 'api_data');
        }
        
        // Clear local file
        if (file_exists(DATA_FILE)) {
            unlink(DATA_FILE);
        }
    }

    public function getPaginatedData($currentPage = 1, $itemsPerPage = 100) {
        $data = $this->getData();
        if (!$data) {
            return [
                'msg' => 'OK',
                'server_time' => date('Y-m-d H:i:s'),
                'status' => 200,
                'result' => [
                    'total_pages' => 0,
                    'results_total' => 0,
                    'results' => 0,
                    'files' => []
                ]
            ];
        }

        $totalItems = count($data);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $offset = ($currentPage - 1) * $itemsPerPage;
        $items = array_slice($data, $offset, $itemsPerPage);

        return [
            'msg' => 'OK',
            'server_time' => date('Y-m-d H:i:s'),
            'status' => 200,
            'result' => [
                'total_pages' => $totalPages,
                'results_total' => $totalItems,
                'results' => count($items),
                'files' => $items
            ]
        ];
    }
}
?>