<?php
session_start();
require 'db.php';

// 查詢熱門推薦餐廳 (計算平均評分並排序) [cite: 13]
$sql = "SELECT r.*, AVG(rv.rating) as avg_rating, COUNT(rv.review_id) as review_count 
        FROM Restaurants r 
        LEFT JOIN Reviews rv ON r.restaurant_id = rv.restaurant_id 
        GROUP BY r.restaurant_id 
        ORDER BY avg_rating DESC 
        LIMIT 3";
$stmt = $db->query($sql);
$top_restaurants = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>首頁 - 海大餐飲評分系統</title>
    <style>
        body { font-family: 'Microsoft JhengHei', sans-serif; background-color: #f4f4f4; margin: 0; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; align-items: center; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .hero { background: #333; color: white; padding: 60px 20px; text-align: center; background-image: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://via.placeholder.com/1200x400?text=NTOU+Food'); background-size: cover; }
        .search-box input { padding: 10px; width: 300px; border-radius: 5px; border: none; }
        .search-box button { padding: 10px 20px; background: #005c97; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .container { max-width: 1000px; margin: 20px auto; padding: 20px; }
        .section-title { border-bottom: 2px solid #005c97; padding-bottom: 10px; margin-bottom: 20px; color: #333; }
        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px; }
        .card { background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .card-body { padding: 15px; }
        .card h3 { margin: 0 0 10px; color: #005c97; }
        .rating { color: #f39c12; font-weight: bold; }
        .btn { display: inline-block; padding: 8px 15px; background: #005c97; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px; }
        .footer { text-align: center; padding: 20px; background: #333; color: white; margin-top: 40px; }
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
            <a href="logout.php">登出</a>
        <?php else: ?>
            <a href="login.php">登入/註冊</a>
        <?php endif; ?>
    </div>
</div>

<div class="hero">
    <h1>探索海大周邊美食</h1>
    <form action="restaurants.php" method="GET" class="search-box">
        <input type="text" name="search" placeholder="搜尋餐廳名稱或類別...">
        <button type="submit">搜尋</button>
    </form>
</div>

<div class="container">
    <h2 class="section-title">熱門推薦餐廳</h2>
    <div class="card-grid">
        <?php foreach($top_restaurants as $rest): ?>
        <div class="card">
            <div class="card-body">
                <h3><?php echo htmlspecialchars($rest['name']); ?></h3>
                <p>類別：<?php echo htmlspecialchars($rest['category']); ?></p>
                <div class="rating">
                    ★ <?php echo number_format($rest['avg_rating'], 1); ?> 
                    <small>(<?php echo $rest['review_count']; ?> 則評論)</small>
                </div>
                <p><?php echo htmlspecialchars(mb_substr($rest['address'], 0, 15)) . '...'; ?></p>
                <a href="restaurant_detail.php?id=<?php echo $rest['restaurant_id']; ?>" class="btn">查看詳情</a>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="footer">
    Copyright 2025 海大餐飲評分系統
</div>

</body>
</html>