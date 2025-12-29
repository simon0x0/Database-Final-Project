<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$stmt = $db->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>個人頁面 - 海大餐飲評分系統</title>
    <style>
        body { font-family: 'Microsoft JhengHei', sans-serif; background: #f4f4f4; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .container { max-width: 600px; margin: 40px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .profile-header { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
        .info-group { margin-bottom: 15px; font-size: 1.1em; }
        .info-label { font-weight: bold; color: #555; width: 100px; display: inline-block; }
        .action-buttons { margin-top: 30px; display: flex; gap: 10px; justify-content: center; }
        .btn { padding: 10px 20px; border-radius: 5px; text-decoration: none; color: white; }
        .btn-edit { background-color: #005c97; }
        .btn-reviews { background-color: #28a745; }
        .btn-logout { background-color: #dc3545; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">海大餐飲評分網</div>
    <div><a href="index.php">回首頁</a></div>
</div>

<div class="container">
    <div class="profile-header">
        <h2>歡迎回來，<?php echo htmlspecialchars($user['username']); ?></h2>
        <p>這是您的個人資料頁面</p>
    </div>

    <div class="info-group">
        <span class="info-label">學號/帳號:</span>
        <?php echo htmlspecialchars($user['username']); ?>
    </div>
    
    <div class="info-group">
        <span class="info-label">Email:</span>
        <?php echo htmlspecialchars($user['email']); ?>
    </div>

    <div class="info-group">
        <span class="info-label">加入時間:</span>
        <?php echo htmlspecialchars($user['created_at']); ?>
    </div>

    <div class="action-buttons">
        <a href="edit_profile.php" class="btn btn-edit">修改個人資料</a>
        <a href="my_reviews.php" class="btn btn-reviews">我的評論列表</a>
        <a href="logout.php" class="btn btn-logout">登出系統</a>
    </div>
</div>

</body>
</html>
