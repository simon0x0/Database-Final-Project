<?php
// restaurant_detail.php
session_start();
require 'db.php';

// 檢查是否有帶入 ID
if (!isset($_GET['id'])) {
    header("Location: restaurants.php");
    exit();
}

$r_id = $_GET['id'];

// 1. 查詢餐廳基本資料
$stmt = $db->prepare("SELECT * FROM Restaurants WHERE restaurant_id = ?");
$stmt->execute([$r_id]);
$restaurant = $stmt->fetch();

if (!$restaurant) {
    die("找不到該餐廳資料");
}

// 2. 查詢該餐廳的所有評論
$sql_reviews = "SELECT rv.*, u.username 
                FROM Reviews rv 
                JOIN Users u ON rv.user_id = u.user_id 
                WHERE rv.restaurant_id = ? 
                ORDER BY rv.review_at DESC";
$stmt_rv = $db->prepare($sql_reviews);
$stmt_rv->execute([$r_id]);
$reviews = $stmt_rv->fetchAll();

// 計算平均分數
$avg_rating = 0;
if (count($reviews) > 0) {
    $sum = 0;
    foreach ($reviews as $r) $sum += $r['rating'];
    $avg_rating = $sum / count($reviews);
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($restaurant['name']); ?> - 詳細資料</title>
    <style>
        body { font-family: 'Microsoft JhengHei', sans-serif; background-color: #f4f4f4; margin: 0; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 800px; margin: 30px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        
        .rest-header { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .rest-title { font-size: 2em; color: #333; font-weight: bold; margin-bottom: 10px; }
        .rest-meta { color: #666; margin-bottom: 8px; font-size: 1.1em; }
        .rating-big { font-size: 1.5em; color: #f39c12; font-weight: bold; }

        /* 菜單圖片樣式 */
        .menu-section {
            margin-top: 20px;
            padding: 15px;
            background-color: #fafafa;
            border-radius: 8px;
            border: 1px dashed #ccc;
        }
        .menu-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
            display: block;
        }
        .menu-img {
            max-width: 100%; /* 限制寬度不超過容器 */
            height: auto;    /* 高度自動調整，保持比例 */
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: block;  /* 讓圖片獨佔一行 */
        }

        .review-section { margin-top: 30px; }
        .review-item { border-bottom: 1px solid #f0f0f0; padding: 15px 0; }
        .review-user { font-weight: bold; color: #005c97; }
        .review-time { float: right; color: #999; font-size: 0.85em; }
        .review-content { margin-top: 8px; line-height: 1.6; color: #444; }
        .star-rating { color: #f39c12; }

        .btn-action { display: inline-block; padding: 10px 20px; background: #005c97; color: white; text-decoration: none; border-radius: 5px; margin-top: 15px; }
        .btn-action:hover { background: #004a7c; }
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
    <div class="rest-header">
        <div class="rest-title"><?php echo htmlspecialchars($restaurant['name']); ?></div>
        
        <div class="rest-meta">類別：<?php echo htmlspecialchars($restaurant['category']); ?></div>
        <div class="rest-meta">電話：<?php echo htmlspecialchars($restaurant['phone']); ?></div>
        <div class="rest-meta">地址：<?php echo htmlspecialchars($restaurant['address']); ?></div>

        <?php if (!empty($restaurant['menu_path'])): ?>
            <div class="menu-section">
                <span class="menu-title">菜單：</span>
                <img src="menu_image/<?php echo htmlspecialchars($restaurant['menu_path']); ?>" 
                     alt="<?php echo htmlspecialchars($restaurant['name']); ?> 菜單" 
                     class="menu-img">
            </div>
        <?php endif; ?>
        <br>
        <div>
            <span class="rating-big"><?php echo number_format($avg_rating, 1); ?> ★</span>
            <span style="color: #888;"> (共 <?php echo count($reviews); ?> 則評論)</span>
        </div>

        <a href="submit_review.php?restaurant_id=<?php echo $r_id; ?>" class="btn-action">
            我要評分 / 修改評論
        </a>
    </div>

    <div class="review-section">
        <h3>訪客評論</h3>
        <?php if (count($reviews) == 0): ?>
            <p style="color: #777;">目前尚無評論，快來搶頭香！</p>
        <?php else: ?>
            <?php foreach($reviews as $rv): ?>
                <div class="review-item">
                    <div>
                        <span class="review-user"><?php echo htmlspecialchars($rv['username']); ?></span>
                        <span class="star-rating">
                            <?php echo str_repeat("★", $rv['rating']) . str_repeat("☆", 5 - $rv['rating']); ?>
                        </span>
                        <span class="review-time"><?php echo $rv['review_at']; ?></span>
                    </div>
                    <div class="review-content">
                        <?php echo nl2br(htmlspecialchars($rv['comment'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</body>
</html>