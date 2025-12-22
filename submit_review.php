<?php
// submit_review.php
session_start();
require 'db.php';

// 1. 權限檢查：沒登入就踢去登入頁
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$r_id = $_GET['restaurant_id'] ?? $_POST['restaurant_id'] ?? 0;

if (!$r_id) {
    die("錯誤的餐廳 ID");
}

// 2. 處理表單送出 (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = (int)$_POST['rating'];
    $comment = $_POST['comment'];
    
    // 檢查是否已存在評論
    $check = $db->prepare("SELECT review_id FROM Reviews WHERE user_id = ? AND restaurant_id = ?");
    $check->execute([$user_id, $r_id]);
    $existing = $check->fetch();

    if ($existing) {
        // [修改] Update
        $sql = "UPDATE Reviews SET rating = ?, comment = ?, review_at = NOW() WHERE review_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$rating, $comment, $existing['review_id']]);
    } else {
        // [新增] Insert
        $sql = "INSERT INTO Reviews (user_id, restaurant_id, rating, comment) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$user_id, $r_id, $rating, $comment]);
    }

    // 完成後跳轉回餐廳詳細頁
    header("Location: restaurant_detail.php?id=" . $r_id);
    exit();
}

// 3. 獲取頁面顯示所需資料 (GET)
// (A) 餐廳名稱
$stmt_rest = $db->prepare("SELECT name FROM Restaurants WHERE restaurant_id = ?");
$stmt_rest->execute([$r_id]);
$rest_name = $stmt_rest->fetchColumn();

// (B) 檢查是否有舊評論 (為了預設填入表單)
$stmt_old = $db->prepare("SELECT * FROM Reviews WHERE user_id = ? AND restaurant_id = ?");
$stmt_old->execute([$user_id, $r_id]);
$my_review = $stmt_old->fetch();

// 設定預設值
$current_rating = $my_review ? $my_review['rating'] : 5;
$current_comment = $my_review ? $my_review['comment'] : '';
$action_text = $my_review ? "修改評論" : "新增評論";
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title><?php echo $action_text; ?> - 海大餐飲評分系統</title>
    <style>
        body { font-family: 'Microsoft JhengHei', sans-serif; background-color: #f4f4f4; margin: 0; }
        .navbar { background: linear-gradient(135deg, #005c97, #363795); padding: 15px; color: white; display: flex; justify-content: space-between; }
        .navbar a { color: white; text-decoration: none; margin-left: 15px; font-weight: bold; }
        .container { max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        
        h2 { border-bottom: 2px solid #eee; padding-bottom: 10px; color: #333; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: bold; color: #555; }
        
        select, textarea { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; font-size: 16px; box-sizing: border-box; }
        textarea { height: 120px; resize: vertical; }
        
        .btn-submit { width: 100%; background: #28a745; color: white; border: none; padding: 12px; font-size: 1.1em; border-radius: 5px; cursor: pointer; }
        .btn-submit:hover { background: #218838; }
        .btn-cancel { display: block; text-align: center; margin-top: 15px; color: #666; text-decoration: none; }
    </style>
</head>
<body>

<div class="navbar">
    <div class="logo">海大餐飲評分網</div>
    <div class="menu">
        <a href="index.php">首頁</a>
        <a href="profile.php">個人頁面</a>
    </div>
</div>

<div class="container">
    <h2><?php echo $action_text; ?>：<?php echo htmlspecialchars($rest_name); ?></h2>
    
    <form action="" method="POST">
        <input type="hidden" name="restaurant_id" value="<?php echo $r_id; ?>">

        <div class="form-group">
            <label>評分 (1-5星)</label>
            <select name="rating">
                <option value="5" <?php if($current_rating==5) echo 'selected'; ?>>★★★★★ (5星 - 非常滿意)</option>
                <option value="4" <?php if($current_rating==4) echo 'selected'; ?>>★★★★☆ (4星 - 滿意)</option>
                <option value="3" <?php if($current_rating==3) echo 'selected'; ?>>★★★☆☆ (3星 - 普通)</option>
                <option value="2" <?php if($current_rating==2) echo 'selected'; ?>>★★☆☆☆ (2星 - 不滿意)</option>
                <option value="1" <?php if($current_rating==1) echo 'selected'; ?>>★☆☆☆☆ (1星 - 非常糟糕)</option>
            </select>
        </div>

        <div class="form-group">
            <label>評論內容</label>
            <textarea name="comment" placeholder="寫下你的用餐體驗..."><?php echo htmlspecialchars($current_comment); ?></textarea>
        </div>

        <button type="submit" class="btn-submit">送出評論</button>
        <a href="restaurant_detail.php?id=<?php echo $r_id; ?>" class="btn-cancel">取消</a>
    </form>
</div>

</body>
</html>