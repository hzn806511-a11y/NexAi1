<?php
session_start();
header('Content-Type: application/json');

function json_response($success, $message, $data = []) {
    $response = ['success' => $success, 'message' => $message];
    if (!empty($data)) {
        $response = array_merge($response, $data);
    }
    echo json_encode($response);
    exit();
}

if (!isset($_SESSION['user']['id'])) {
    json_response(false, 'لطفاً ابتدا وارد حساب کاربری خود شوید.');
}

// بررسی وجود فایل و نبود خطا
if (!isset($_FILES['profile_pic']) || $_FILES['profile_pic']['error'] !== UPLOAD_ERR_OK) {
    json_response(false, 'خطایی در هنگام آپلود فایل رخ داد. لطفاً فایل دیگری را امتحان کنید.');
}

$file = $_FILES['profile_pic'];
$userId = $_SESSION['user']['id'];

// 1. تنظیمات و اعتبارسنجی فایل
$uploadDir = 'uploads/profiles/';
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
$maxSize = 5 * 1024 * 1024; // 5 MB

if (!in_array($file['type'], $allowedTypes)) {
    json_response(false, 'فرمت فایل مجاز نیست. فقط (JPG, PNG, GIF)');
}

if ($file['size'] > $maxSize) {
    json_response(false, 'حجم فایل بیش از حد مجاز (5 مگابایت) است.');
}

// 2. ساخت نام منحصر به فرد برای فایل
$fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFileName = uniqid('user_' . $userId . '_', true) . '.' . $fileExtension;
$destination = $uploadDir . $newFileName;

// اطمینان از وجود پوشه آپلود
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

// 3. انتقال فایل به پوشه مقصد
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    json_response(false, 'خطا در ذخیره‌سازی فایل.');
}

// 4. اتصال به دیتابیس و آپدیت رکورد کاربر
$host = "localhost"; 
$username_db = "root";      
$password_db = "";        
$dbname = "blog";   

try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username_db, $password_db);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // حذف عکس پروفایل قدیمی (اختیاری ولی توصیه شده)
    $stmt = $connection->prepare("SELECT profile_pic FROM users WHERE id = :id");
    $stmt->execute(['id' => $userId]);
    $oldPic = $stmt->fetchColumn();
    if ($oldPic && file_exists($uploadDir . $oldPic)) {
        unlink($uploadDir . $oldPic);
    }

    // آپدیت نام فایل جدید در دیتابیس
    $stmt = $connection->prepare("UPDATE users SET profile_pic = :profile_pic WHERE id = :id");
    $stmt->execute(['profile_pic' => $newFileName, 'id' => $userId]);

    // 5. آپدیت سشن با نام عکس جدید
    $_SESSION['user']['profile_pic'] = $newFileName;

    json_response(true, 'عکس پروفایل با موفقیت آپلود شد.', ['imagePath' => $destination]);

} catch (PDOException $e) {
    // در صورت بروز خطا در دیتابیس، فایل آپلود شده را حذف کن
    unlink($destination); 
    json_response(false, 'خطایی در دیتابیس رخ داد.');
}
?>