<?php
class RedisCache {
    private $redis;
    private $enabled;

    public function __construct() {
        $this->enabled = REDIS_ENABLED;
        if ($this->enabled) {
            $this->redis = new Redis();
            try {
                $this->redis->connect(REDIS_HOST, REDIS_PORT);
            } catch (Exception $e) {
                $this->enabled = false;
                error_log("Redis connection failed: " . $e->getMessage());
            }
        }
    }

    public function set($key, $value, $ttl = 3600) {
        if (!$this->enabled) return false;
        try {
            return $this->redis->setex(REDIS_PREFIX . $key, $ttl, serialize($value));
        } catch (Exception $e) {
            error_log("Redis set failed: " . $e->getMessage());
            return false;
        }
    }

    public function get($key) {
        if (!$this->enabled) return false;
        try {
            $data = $this->redis->get(REDIS_PREFIX . $key);
            return $data ? unserialize($data) : false;
        } catch (Exception $e) {
            error_log("Redis get failed: " . $e->getMessage());
            return false;
        }
    }

    public function delete($key) {
        if (!$this->enabled) return false;
        try {
            return $this->redis->del(REDIS_PREFIX . $key);
        } catch (Exception $e) {
            error_log("Redis delete failed: " . $e->getMessage());
            return false;
        }
    }

    public function isEnabled() {
        return $this->enabled;
    }
}
?>
