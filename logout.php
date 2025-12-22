<?php
session_start();
session_unset(); // 釋放所有會話變數
session_destroy(); // 銷毀會話

// 登出後導向登入頁面
header("Location: login.php");
exit();
?>