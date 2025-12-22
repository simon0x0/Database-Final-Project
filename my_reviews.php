<?php
// my_reviews.php
session_start();
require 'db.php';

// 1. 檢查登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 2. 處理刪除請求 (簡單實作)
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    // 確保只能刪除自己的評論
    $del_stmt = $db->prepare("DELETE FROM Reviews WHERE review_id = ? AND user_id = ?");
    $del_stmt->execute([$delete_id, $user_id]);
    header("Location: my_reviews.php"); // 重新整理頁面
    exit();
}

// 3. 查詢我的所有評論 (JOIN 餐廳名稱)
$sql = "SELECT Reviews.*, Restaurants.name AS restaurant_name 
        FROM Reviews 
        JOIN Restaurants ON Reviews.restaurant_id = Restaurants.restaurant_id 
        WHERE Reviews.user_id = ? 
        ORDER BY Reviews.review_at DESC";
$stmt = $db->prepare($sql);
$stmt->execute([$user_id]);
$reviews = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>我的評論 - 海大餐飲評分系統</title>
    <style>
        body { font-family: 'Microsoft JhengHei', sans-serif; background: #f4f4f4; margin: 0; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .container { max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }

        .review-card { border: 1px solid #ddd; padding: 20px; border-radius: 8px; margin-bottom: 20px; background: #fff; display: flex; flex-direction: column; }
        .review-header { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 10px; margin-bottom: 10px; }
        .restaurant-name { font-size: 1.2em; font-weight: bold; color: #333; }
        .rating { color: #f39c12; font-weight: bold; }
        .review-date { color: #888; font-size: 0.9em; }
        .review-content { color: #555; line-height: 1.6; }
        
        .review-actions { margin-top: 15px; text-align: right; }
        .btn-sm { padding: 5px 10px; font-size: 0.9em; border-radius: 4px; text-decoration: none; color: white; margin-left: 5px; }
        .btn-edit { background-color: #005c97; }
        .btn-delete { background-color: #dc3545; }
        
        .no-reviews { text-align: center; color: #777; padding: 40px; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">海大餐飲評分網</div>
    <div>
        <a href="index.php">回首頁</a>
        <a href="profile.php">個人頁面</a>
    </div>
</div>

<div class="container">
    <h2>我的評論列表</h2>

    <?php if (count($reviews) > 0): ?>
        <?php foreach ($reviews as $review): ?>
            <div class="review-card">
                <div class="review-header">
                    <div>
                        <span class="restaurant-name"><?php echo htmlspecialchars($review['restaurant_name']); ?></span>
                        <span class="rating">★ <?php echo $review['rating']; ?></span>
                    </div>
                    <span class="review-date"><?php echo $review['review_at']; ?></span>
                </div>
                
                <div class="review-content">
                    <?php echo nl2br(htmlspecialchars($review['comment'])); ?>
                </div>

                <div class="review-actions">
                    <a href="submit_review.php?restaurant_id=<?php echo $review['restaurant_id']; ?>" class="btn-sm btn-edit">編輯/重評</a>
                    
                    <a href="my_reviews.php?delete_id=<?php echo $review['review_id']; ?>" 
                       class="btn-sm btn-delete" 
                       onclick="return confirm('確定要刪除這則評論嗎？');">刪除</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="no-reviews">
            <h3>您還沒有發表過任何評論</h3>
            <p>去首頁找間餐廳評論看看吧！</p>
            <a href="index.php" style="color: #005c97;">前往餐廳列表</a>
        </div>
    <?php endif; ?>

    <div style="margin-top: 20px; text-align: center;">
        <a href="profile.php" style="color: #666; text-decoration: none;">返回個人頁面</a>
    </div>
</div>

</body>
</html>