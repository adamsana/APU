<?php
require_once __DIR__.'/../src/DataManager.php';

$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$dataManager = new DataManager();
$response = $dataManager->getPaginatedData($currentPage);
$result = $response['result'];

ob_start();
?>
    <h1>Data List</h1>
    
    <div class="cache-info">
        <p><strong>Cache Status:</strong></p>
        <ul>
            <li>Redis: <?= REDIS_ENABLED ? 'Enabled' : 'Disabled' ?></li>
            <li>Local Data: <?= file_exists(DATA_FILE) ? 
                'Last updated ' . date('Y-m-d H:i:s', filemtime(DATA_FILE)) : 
                'Not available' ?></li>
        </ul>
        
        <div class="btn-group">
            <a href="?refresh_cache=1" class="btn btn-danger">Refresh Cache</a>
            <a href="api/documentation" class="btn">API Docs</a>
            <a href="api/cache-info" class="btn">Cache Info</a>
        </div>
    </div>
    
    <?php foreach ($result['items'] as $item): ?>
    <div class="card">
        <h3>
            <a href="detail?file_code=<?= urlencode($item['file_code'] ?? '') ?>">
                <?= htmlspecialchars($item['title'] ?? 'No Title') ?>
            </a>
        </h3>
        <p><strong>File Code:</strong> <?= htmlspecialchars($item['file_code'] ?? '') ?></p>
        <p><strong>Views:</strong> <?= number_format($item['views'] ?? 0) ?></p>
        <p><a href="detail?file_code=<?= urlencode($item['file_code'] ?? '') ?>" class="btn">View Details</a></p>
    </div>
    <?php endforeach; ?>
    
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage-1 ?>">← Previous</a>
        <?php endif; ?>
        
        <?php for ($i = 1; $i <= $result['pagination']['total_pages']; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <strong><?= $i ?></strong>
            <?php else: ?>
                <a href="?page=<?= $i ?>"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($currentPage < $result['pagination']['total_pages']): ?>
            <a href="?page=<?= $currentPage+1 ?>">Next →</a>
        <?php endif; ?>
    </div>
<?php
$content = ob_get_clean();
$title = 'Data List';
require_once 'layout.php';
?>