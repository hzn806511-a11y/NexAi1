<?php
session_start();
header('Content-Type: application/json'); // مشخص کردن نوع خروجی به صورت JSON

// تابع برای ارسال پاسخ‌های JSON
function json_response($success, $message, $data = []) {
    $response = ['success' => $success, 'message' => $message];
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit();
}

// 1. بررسی اینکه کاربر وارد شده است یا خیر
if (!isset($_SESSION['user']['id'])) {
    json_response(false, 'لطفاً ابتدا وارد حساب کاربری خود شوید.');
}

// 2. اطلاعات اتصال به دیتابیس
$host = "localhost"; 
$username_db = "root";      
$password_db = "";        
$dbname = "blog";   

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    json_response(false, 'خطا در اتصال به دیتابیس.');
}

// 3. دریافت اطلاعات از فرم
$userId = $_SESSION['user']['id'];
$newEmail = trim($_POST['email']);
$newPassword = $_POST['new_password'];
$confirmPassword = $_POST['confirm_password'];
$currentPassword = $_POST['current_password'];

// 4. اعتبارسنجی‌ها
if (empty($currentPassword)) {
    json_response(false, 'برای ذخیره تغییرات، وارد کردن رمز عبور فعلی الزامی است.');
}

if (!empty($newPassword) && strlen($newPassword) < 6) {
    json_response(false, 'رمز عبور جدید باید حداقل ۶ کاراکتر باشد.');
}

if ($newPassword !== $confirmPassword) {
    json_response(false, 'رمز عبور جدید و تکرار آن با هم مطابقت ندارند.');
}

if (empty($newEmail) && empty($newPassword)) {
    json_response(false, 'هیچ تغییری برای ذخیره کردن وجود ندارد.');
}

try {
    // 5. بررسی صحت رمز عبور فعلی
    $stmt = $connection->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($currentPassword, $user['password'])) {
        json_response(false, 'رمز عبور فعلی شما اشتباه است.');
    }

    // 6. آماده‌سازی کوئری آپدیت
    $updateQuery = "UPDATE users SET ";
    $params = ['id' => $userId];
    $updates = [];

    if (!empty($newEmail)) {
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
             json_response(false, 'فرمت ایمیل وارد شده صحیح نیست.');
        }
        $updates[] = "Email = :email";
        $params['email'] = $newEmail;
    }

    if (!empty($newPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updates[] = "password = :password";
        $params['password'] = $hashedPassword;
    }

    $updateQuery .= implode(', ', $updates) . " WHERE id = :id";
    $stmt = $connection->prepare($updateQuery);
    $stmt->execute($params);

    // 7. آپدیت کردن اطلاعات در سشن
    if (!empty($newEmail)) {
        $_SESSION['user']['email'] = $newEmail;
    }

    json_response(true, 'اطلاعات شما با موفقیت به‌روزرسانی شد.', ['newEmail' => $newEmail]);

} catch (PDOException $e) {
    // بررسی خطای تکراری بودن ایمیل
    if ($e->getCode() == 23000) { 
        json_response(false, 'این ایمیل قبلاً توسط کاربر دیگری ثبت شده است.');
    }
    json_response(false, 'خطایی در سرور رخ داد. لطفاً بعداً تلاش کنید.');
}
?>