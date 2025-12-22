<?php
// edit_profile.php
session_start();
require 'db.php';

// 1. 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = '';
$user_id = $_SESSION['user_id'];

// 2. 處理表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // 基本驗證
    if (empty($email)) {
        $message = '<div class="alert error">Email 不能為空</div>';
    } elseif (!empty($new_password) && ($new_password !== $confirm_password)) {
        $message = '<div class="alert error">兩次密碼輸入不一致</div>';
    } else {
        // 準備 SQL
        try {
            if (!empty($new_password)) {
                // 如果有輸入新密碼，同時更新 Email 和密碼
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("UPDATE Users SET email = ?, password_hash = ? WHERE user_id = ?");
                $stmt->execute([$email, $password_hash, $user_id]);
            } else {
                // 只更新 Email
                $stmt = $db->prepare("UPDATE Users SET email = ? WHERE user_id = ?");
                $stmt->execute([$email, $user_id]);
            }
            $message = '<div class="alert success">資料更新成功！</div>';
        } catch (PDOException $e) {
            $message = '<div class="alert error">更新失敗：' . htmlspecialchars($e->getMessage()) . '</div>';
        }
    }
}

// 3. 獲取目前使用者資料 (為了顯示在表單中)
$stmt = $db->prepare("SELECT * FROM Users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>修改個人資料 - 海大餐飲評分系統</title>
    <style>
        /* 沿用 profile.php 的風格 */
        body { font-family: 'Microsoft JhengHei', sans-serif; background: #f4f4f4; margin: 0; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; }
        .container { max-width: 500px; margin: 40px auto; background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; border-bottom: 2px solid #eee; padding-bottom: 20px; }
        
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        
        .btn-submit { width: 100%; padding: 12px; background-color: #005c97; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn-submit:hover { background-color: #004a7c; }
        .back-link { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
        
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; text-align: center; }
        .alert.error { background-color: #f8d7da; color: #721c24; }
        .alert.success { background-color: #d4edda; color: #155724; }
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
    <h2>修改個人資料</h2>
    <?php echo $message; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label>使用者名稱 (不可修改)</label>
            <input type="text" value="<?php echo htmlspecialchars($user['username']); ?>" disabled style="background-color: #e9ecef;">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="form-group">
            <label for="password">新密碼 (若不修改請留空)</label>
            <input type="password" id="password" name="password" placeholder="輸入新密碼">
        </div>

        <div class="form-group">
            <label for="confirm_password">確認新密碼</label>
            <input type="password" id="confirm_password" name="confirm_password" placeholder="再次輸入新密碼">
        </div>

        <button type="submit" class="btn-submit">儲存變更</button>
    </form>
    
    <a href="profile.php" class="back-link">取消並返回</a>
</div>

</body>
</html>