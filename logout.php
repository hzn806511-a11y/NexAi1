<?php
session_start();

// از بین بردن تمام متغیرهای سشن
$_SESSION = array();

// حذف کوکی سشن در صورت وجود
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// نابود کردن سشن
session_destroy();

// انتقال کاربر به صفحه لاگین
header("Location: login.php");
exit();
?>