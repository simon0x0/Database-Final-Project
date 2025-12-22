<?php
session_start();
require 'db.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $db->prepare("SELECT * FROM Users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        header("Location: index.php");
        exit(); 
    } else {
        echo "<script>alert('帳號或密碼錯誤！');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入 - 海大餐飲評分系統</title>
    <style>
        /* 基礎樣式重置 */
        body {
            font-family: 'Segoe UI', Microsoft JhengHei, sans-serif;
            background: linear-gradient(135deg, #005c97, #363795); /* 海洋藍漸層 */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }

        /* 登入卡片容器 */
        .login-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .login-container h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 24px;
        }

        /* 輸入框組合 */
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            color: #666;
            font-weight: bold;
        }

        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box; /* 確保 padding 不會撐破寬度 */
            transition: border-color 0.3s;
        }

        .input-group input:focus {
            outline: none;
            border-color: #005c97;
        }

        /* 按鈕樣式 */
        button {
            width: 100%;
            padding: 12px;
            background-color: #005c97;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        button:hover {
            background-color: #004573;
        }

        /* 下方連結 */
        .footer-links {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .footer-links a {
            color: #005c97;
            text-decoration: none;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>海大餐飲評分系統</h2>
    
    <form method="post">
        <div class="input-group">
            <label>帳號 (Username)</label>
            <input type="text" name="username" placeholder="請輸入帳號" required>
        </div>
        
        <div class="input-group">
            <label>密碼 (Password)</label>
            <input type="password" name="password" placeholder="請輸入密碼" required>
        </div>
        
        <button type="submit">立即登入</button>
    </form>

    <div class="footer-links">
        還沒有帳號？ <a href="register.php">點此註冊</a>
    </div>
</div>

</body>
</html>