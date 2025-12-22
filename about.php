<?php
session_start();
// 這裡可以選擇性 require 'db.php'，如果之後要在關於我們顯示統計數據（如：目前有幾家餐廳）
?>

<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>關於我們 - 海大餐飲評分系統</title>
    <style>
        body {
            font-family: 'Segoe UI', Microsoft JhengHei, sans-serif;
            background: linear-gradient(135deg, #005c97, #363795);
            margin: 0;
            padding: 40px 20px;
            color: #333;
            line-height: 1.8;
            display: flex;
            justify-content: center;
        }

        .about-container {
            background-color: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 800px;
        }

        h1 {
            color: #005c97;
            text-align: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
            margin-top: 0;
        }

        .project-info {
            background-color: #f0f7ff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 6px solid #005c97;
        }

        .project-info p {
            margin: 5px 0;
            font-size: 1.1em;
        }

        h2 {
            color: #444;
            font-size: 1.4em;
            margin-top: 25px;
        }

        .intro-text {
            text-align: justify;
            margin-bottom: 25px;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }

        @media (max-width: 600px) {
            .feature-grid { grid-template-columns: 1fr; }
        }

        .feature-item {
            background: #fff;
            border: 1px solid #eee;
            padding: 15px;
            border-radius: 12px;
            transition: transform 0.3s;
        }

        .feature-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .feature-item strong {
            color: #005c97;
            display: block;
            margin-bottom: 8px;
            font-size: 1.1em;
        }

        .nav-links {
            text-align: center;
            margin-top: 40px;
        }

        .btn {
            display: inline-block;
            padding: 10px 25px;
            background-color: #005c97;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #004573;
        }
    </style>
</head>
<body>

<div class="about-container">
    <h1>關於系統</h1>

    <div class="project-info">
        <p><strong>課程名稱：</strong> 資料庫系統</p>
        <p><strong>專案名稱：</strong> 海洋大學附近餐飲評分系統</p>
    </div>

    <h2>系統願景</h2>
    <p class="intro-text">
        本計畫旨在為「國立臺灣海洋大學」師生及訪客，打造一個專屬的餐飲資訊分享平台。
        基隆海大周邊匯集了各式特色美食，然而品質與評價眾多且零散。
        我們透過資料庫技術，將零散的資訊整合，讓每一份食記與評分都能成為他人重要的決策參考。
    </p>

    <h2>四大核心功能</h2>
    <div class="feature-grid">
        <div class="feature-item">
            <strong>👤 使用者管理</strong>
            提供專屬帳號註冊與登入功能，確保評價的真實性與個人資料的安全維護。
        </div>
        <div class="feature-item">
            <strong>🍴 餐廳資訊導覽</strong>
            整合海大周邊餐廳列表，快速查詢地址、電話及菜單類型，解決「今天吃什麼」的煩惱。
        </div>
        <div class="feature-item">
            <strong>⭐ 深度評分評論</strong>
            使用者在用餐後可進行 1-5 星級評分並撰寫心得，分享最真實的味蕾體驗。
        </div>
        <div class="feature-item">
            <strong>📊 智能評價彙總</strong>
            系統自動計算餐廳平均得分並彙整評論，協助使用者精準避雷，發掘隱藏美食。
        </div>
    </div>

    <div class="nav-links">
        <a href="welcome.php" class="btn">返回系統首頁</a>
    </div>
</div>

</body>
</html>