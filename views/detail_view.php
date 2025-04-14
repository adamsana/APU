<?php
if (!isset($_GET['file_code'])) {
    header("Location: list");
    exit;
}

$item = findItemByFileCode($_GET['file_code']);

if (!$item) {
    header("Location: list");
    exit;
}

ob_start();
?>
    <a href="list" class="btn">← Back to List</a>
    
    <div class="card">
        <h2><?= htmlspecialchars($item['title']) ?></h2>
        
        <div class="detail-row">
            <div class="detail-label">File Code:</div>
            <div><?= htmlspecialchars($item['file_code']) ?></div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Views:</div>
            <div><?= number_format($item['views']) ?></div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Size:</div>
            <div><?= formatBytes($item['size']) ?></div>
        </div>
        
        <div class="detail-row">
            <div class="detail-label">Uploaded:</div>
            <div><?= htmlspecialchars($item['uploaded']) ?></div>
        </div>
        
        <?php if ($item['single_img']): ?>
        <div class="detail-row">
            <div class="detail-label">Thumbnail:</div>
            <div><img src="<?= htmlspecialchars($item['single_img']) ?>" class="thumbnail"></div>
        </div>
        <?php endif; ?>
    </div>
    
    <a href="list" class="btn">← Back to List</a>
<?php
$content = ob_get_clean();
$title = 'Detail: ' . htmlspecialchars($item['title']);
require_once 'layout.php';
?>