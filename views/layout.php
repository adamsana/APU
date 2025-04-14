<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'API Data Viewer') ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 1200px; margin: 0 auto; }
        .card { border: 1px solid #ddd; border-radius: 5px; padding: 15px; margin-bottom: 20px; }
        .pagination { margin: 20px 0; display: flex; gap: 5px; }
        .pagination a, .pagination strong { 
            padding: 5px 10px; border: 1px solid #ddd; text-decoration: none; color: #333;
        }
        .pagination strong { background: #f0f0f0; }
        .cache-info { background: #f8f8f8; padding: 10px; margin-bottom: 20px; }
        .btn { 
            display: inline-block; padding: 8px 15px; 
            background: #333; color: white; text-decoration: none; border-radius: 4px;
        }
        .btn-danger { background: #ff4444; }
        .thumbnail { max-width: 100px; max-height: 75px; vertical-align: middle; }
        .detail-row { display: flex; margin-bottom: 10px; }
        .detail-label { font-weight: bold; width: 150px; }
    </style>
</head>
<body>
    <div class="container">
        <?= $content ?>
    </div>
</body>
</html>