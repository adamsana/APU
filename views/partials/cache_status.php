<div class="cache-info">
    <p><strong>Cache Status:</strong> 
        <?php 
        $cacheData = readFromCache();
        echo $cacheData ? 'Active ('.count($cacheData).' items)' : 'Empty';
        ?>
    </p>
    <?php if ($redisCache->isEnabled()): ?>
        <p><strong>Redis Cache:</strong> Active</p>
    <?php else: ?>
        <p><strong>Redis Cache:</strong> Not Available (using file cache)</p>
    <?php endif; ?>
    <a href="?refresh_cache=1" class="btn btn-danger">Refresh Cache</a>
    <a href="api/cache-info" class="btn" target="_blank">Cache Details</a>
</div>
