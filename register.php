<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($email) && !empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO Users (username, password_hash, email) VALUES (?, ?, ?)";
            $stmt = $db->prepare($sql);
            $stmt->execute([$username, $password_hash, $email]);

            // 註冊成功跳轉
            echo "<script>alert('註冊成功！'); window.location.href='login.php';</script>";
            exit(); 

        } catch (PDOException $e) {
            // 檢查是否為重複鍵值的錯誤代碼 (1062)
            if ($e->errorInfo[1] == 1062) {
                // 進一步判斷是哪個欄位重複
                if (strpos($e->getMessage(), 'username') !== false) {
                    echo "<script>alert('此暱稱已被使用，請換一個！'); history.back();</script>";
                } elseif (strpos($e->getMessage(), 'email') !== false) {
                    echo "<script>alert('此 Email 已被註冊！'); history.back();</script>";
                } else {
                    echo "<script>alert('帳號或 Email 重複！'); history.back();</script>";
                }
            } else {
                echo "註冊失敗：" . $e->getMessage();
            }
        }
    } else {
        echo "<script>alert('請填寫所有欄位！'); history.back();</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>註冊 - 海大餐飲評分系統</title>
    <style>
        body {
            font-family: 'Segoe UI', Microsoft JhengHei, sans-serif;
            background: linear-gradient(135deg, #005c97, #363795);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
        }
        .register-container {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }
        .register-container h2 { color: #333; margin-bottom: 25px; }
        .input-group { margin-bottom: 15px; text-align: left; }
        .input-group label { display: block; margin-bottom: 5px; color: #666; font-weight: bold; }
        .input-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #005c97;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover { background-color: #004573; }
        .footer-links { margin-top: 15px; font-size: 14px; }
        .footer-links a { color: #005c97; text-decoration: none; }
    </style>
</head>
<body>

<div class="register-container">
    <h2>加入海大餐飲評分系統</h2>
    <form method="post">
        <div class="input-group">
            <label>暱稱</label>
            <input type="text" name="username" placeholder="想被如何稱呼？" required>
        </div>
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" placeholder="您的電子信箱" required>
        </div>
        <div class="input-group">
            <label>密碼</label>
            <input type="password" name="password" placeholder="建議 6 位數以上" required>
        </div>
        <button type="submit">確認註冊</button>
    </form>
    <div class="footer-links">
        已經有帳號了？ <a href="login.php">點此登入</a>
    </div>
</div>

</body>
</html>