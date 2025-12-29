<?php
session_start();
require 'db.php';

$search = $_GET['search'] ?? '';

if ($search) {
    $sql = "SELECT r.*, AVG(rv.rating) as avg_rating, COUNT(rv.review_id) as review_count 
            FROM Restaurants r 
            LEFT JOIN Reviews rv ON r.restaurant_id = rv.restaurant_id 
            WHERE r.name LIKE ? OR r.category LIKE ?
            GROUP BY r.restaurant_id";
    $stmt = $db->prepare($sql);
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $sql = "SELECT r.*, AVG(rv.rating) as avg_rating, COUNT(rv.review_id) as review_count 
            FROM Restaurants r 
            LEFT JOIN Reviews rv ON r.restaurant_id = rv.restaurant_id 
            GROUP BY r.restaurant_id";
    $stmt = $db->query($sql);
}
$restaurants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>餐廳列表 - 海大餐飲評分系統</title>
    <style>
        body { font-family: 'Microsoft JhengHei', sans-serif; background-color: #f4f4f4; margin: 0; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 800px; margin: 30px auto; padding: 20px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .rest-item { display: flex; border-bottom: 1px solid #eee; padding: 20px 0; align-items: center; }
        .rest-info { flex-grow: 1; }
        .rest-name { font-size: 1.2em; color: #005c97; margin-bottom: 5px; font-weight: bold; }
        .rating-badge { background: #f39c12; color: white; padding: 2px 8px; border-radius: 4px; font-size: 0.9em; }
        .btn-view { padding: 8px 15px; border: 1px solid #005c97; color: #005c97; text-decoration: none; border-radius: 5px; transition: 0.3s; }
        .btn-view:hover { background: #005c97; color: white; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">海大餐飲評分網</div>
    <div class="menu">
        <a href="index.php">首頁</a>
        <a href="restaurants.php">餐廳列表</a>
        <?php if(isset($_SESSION['user_id'])): ?>
            <a href="profile.php"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
        <?php else: ?>
            <a href="login.php">登入</a>
        <?php endif; ?>
    </div>
</div>

<div class="container">
    <h2><?php echo $search ? "搜尋結果： " . htmlspecialchars($search) : "所有餐廳列表"; ?></h2>
    
    <?php if (count($restaurants) == 0): ?>
        <p>找不到符合的餐廳。</p>
    <?php endif; ?>

    <?php foreach($restaurants as $row): ?>
    <div class="rest-item">
        <div class="rest-info">
            <div class="rest-name"><?php echo htmlspecialchars($row['name']); ?></div>
            <div style="color: #666; font-size: 0.9em;">
                <span class="rating-badge"><?php echo number_format($row['avg_rating'], 1); ?> ★</span>
                &nbsp; | &nbsp; <?php echo htmlspecialchars($row['category']); ?> 
                &nbsp; | &nbsp; <?php echo htmlspecialchars($row['address']); ?>
            </div>
        </div>
        <a href="restaurant_detail.php?id=<?php echo $row['restaurant_id']; ?>" class="btn-view">查看詳情</a>
    </div>
    <?php endforeach; ?>
</div>

</body>
</html>
