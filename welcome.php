<?php
session_start();

// 檢查使用者是否已登入，若未登入則導回登入頁面
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>歡迎 - 海洋大學附近餐飲評分系統</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; padding: 20px; }
        .container { max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px; }
        .logout-btn { color: red; text-decoration: none; font-weight: bold; }
        .info-card { background-color: #f9f9f9; padding: 15px; border-left: 5px solid #005c97; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h1>歡迎來到 -> 海洋大學附近餐飲評分系統</h1>
    <p>你好，<strong><?php echo htmlspecialchars($username); ?></strong>！很高興見到你。</p>

    <div class="info-card">
        <h3>系統小資訊</h3>
        <ul>
            <li><strong>所在地：</strong> 基隆市中正區（海洋大學周邊）</li>
            <li><strong>目前功能：</strong> 帳號註冊、安全登入</li>
            <li><strong>即將推出：</strong> 餐廳評價、美食地圖、學生推薦清單</li>
        </ul>
    </div>

    <hr>
    <p>
        <a href="logout.php" class="logout-btn">登出系統</a>
    </p>
</div>

</body>
</html>