<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once './index.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password || !$confirm_password) {
        $message = "لطفاً تمام فیلدها را پر کنید.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "ایمیل نامعتبر است.";
    } elseif ($password !== $confirm_password) {
        $message = "رمز عبور و تأیید رمز عبور مطابقت ندارند.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            global $connection;
            $stmt = $connection->prepare("INSERT INTO `users` (`name`, `Email`, `password`) VALUES (:name, :email, :password)");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashed_password
            ]);

            $_SESSION['user'] = [
                'name' => $name,
                'email' => $email,
                'profile_pic' => 'default-avatar.png'
            ];

            header("Location: dashboard.php");
            exit();
        } catch (PDOException $e) {
            $message = "خطا در ثبت نام: " . $e->getMessage() ." و احتمالا شما قبلا به سایت ما وارد شده اید پس لطفا  از گزینه ورود بلا وارد شوید.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://imagizer.imageshack.com/img923/3161/sxavmO.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600&display=swap" rel="stylesheet">
    <title>ساخت حساب کاربری</title>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        :root {
            --primary: #9c27b0;
            --primary-glow: 0 0 25px rgba(156, 39, 176, .7);
            --bg: #0a0a0a;
            --card: rgba(26, 26, 26, .95);
            --input: rgba(50, 50, 50, .6);
            --text: #f8f9fa;
            --muted: #b0bec5;
            --border: #444;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            background: var(--bg);
            color: var(--text);
            position: relative;
            overflow-x: hidden; /* جلوگیری از اسکرول افقی */
        }

        .video-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .video-bg video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: brightness(.6);
        }

        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(10,10,10,.8), rgba(30,30,30,.6));
        }

        .particle {
            position: fixed;
            background: rgba(156, 39, 176, .3);
            border-radius: 50%;
            pointer-events: none;
            animation: float 8s ease-in-out infinite;
            z-index: 1;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); opacity: .3; }
            50% { transform: translateY(-40px) rotate(180deg); opacity: .7; }
        }

        .main {
            display: flex;
            width: 100%;
            min-height: 100vh; /* مهم: اجازه می‌دهد در صورت نیاز محتوا اسکرول بخورد */
            position: relative;
            z-index: 2;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem; /* کمی پدینگ برای فاصله‌گذاری بهتر */
        }

        .form-box {
            width: 100%;
            max-width: 480px;
            background: var(--card);
            backdrop-filter: blur(16px);
            padding: 2.5rem 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,.8);
            border: 1px solid rgba(156,39,176,.2);
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            overflow: hidden;
            box-shadow: var(--primary-glow);
            border: 3px solid rgba(156,39,176,.4);
            margin: 0 auto;
            transition: transform .3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 1rem 0 .5rem;
            background: linear-gradient(45deg, #bb86fc, #9c27b0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .subtitle {
            text-align: center;
            color: var(--muted);
            font-size: .9rem;
            margin-bottom: 1.5rem;
        }

        .subtitle a {
            color: #bb86fc;
            text-decoration: none;
        }

        .subtitle a:hover {
            text-decoration: underline;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .input-wrap {
            position: relative;
        }

        .form-group input {
            width: 100%;
            padding: 14px 45px 14px 16px;
            background: var(--input);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 1rem;
            transition: all .3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: var(--primary-glow);
            background: rgba(60,60,60,.8);
        }

        .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            font-size: 1.1rem;
            transition: color .3s;
        }

        .form-group input:focus + .input-icon {
            color: var(--primary);
        }

        .btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(45deg, var(--primary), #7b1fa2);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            box-shadow: 0 8px 25px rgba(156,39,176,.3);
            transition: all .4s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(156,39,176,.5);
        }

        .msg {
            margin: 1rem 0;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            font-size: .95rem;
            font-weight: 500;
        }

        .msg.success {
            background: #1b5e20;
            color: #c8e6c9;
            border: 1px solid #4caf50;
        }
        .msg.error {
            background: #b71c1c;
            color: #ffcdd2;
            border: 1px solid #f44336;
        }

        .footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .8rem;
            color: var(--muted);
        }

        .footer a {
            color: #bb86fc;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
            color: white;
        }

        /* استایل‌های ریسپانسیو */
        @media (max-width: 480px) {
            .form-box {
                padding: 2rem 1.5rem;
                margin: 1rem 0; /* افزودن کمی فاصله در بالا و پایین در موبایل */
            }
            .logo {
                width: 100px;
                height: 100px;
            }
            .title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>
<body>
    <div class="video-bg">
        <video autoplay muted loop playsinline>
            <source src="./video/popo.mp4" type="video/mp4">
            <img src="https://imagizer.imageshack.com/img923/3161/sxavmO.png" alt="Fallback">
        </video>
        <div class="video-overlay"></div>
    </div>

    <div class="particle" style="width:7px;height:7px;top:15%;left:20%;animation-delay:0s;"></div>
    <div class="particle" style="width:5px;height:5px;top:60%;left:75%;animation-delay:1.5s;"></div>
    <div class="particle" style="width:8px;height:8px;top:35%;left:50%;animation-delay:3s;"></div>
    <div class="particle" style="width:6px;height:6px;top:80%;left:30%;animation-delay:2s;"></div>

    <div class="main">
        <div class="form-box">
            <div class="logo-wrap">
                <div class="logo">
                    <img src="https://imagizer.imageshack.com/img923/3161/sxavmO.png" alt="Logo">
                </div>
            </div>

            <h1 class="title">ساخت حساب کاربری</h1>
            <p class="subtitle">قبلاً ثبت نام کرده‌اید؟ <a href="./login.php">ورود</a></p>

            <?php if ($message): ?>
                <div class="msg <?= strpos($message, 'خطا') === false ? 'success' : 'error' ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                <div class="form-group">
                    <div class="input-wrap">
                        <input type="text" name="name" required placeholder="نام کاربری">
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="email" name="email" required placeholder="ایمیل">
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" name="password" required placeholder="رمز عبور">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" name="confirm_password" required placeholder="تأیید رمز عبور">
                        <i class="fas fa-lock input-icon"></i>
                    </div>
                </div>

                <button type="submit" class="btn">ثبت نام</button>
            </form>

            <div class="footer">
                <p>با ثبت نام، <a href="#">شرایط خدمات</a> و <a href="#">سیاست حریم خصوصی</a> را می‌پذیرید.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            for (let i = 0; i < 12; i++) {
                const p = Object.assign(document.createElement('div'), { className: 'particle' });
                const size = Math.random() * 6 + 4;
                Object.assign(p.style, {
                    width: size + 'px', height: size + 'px',
                    top: Math.random() * 100 + '%', left: Math.random() * 100 + '%',
                    animationDelay: Math.random() * 6 + 's',
                    animationDuration: (Math.random() * 10 + 8) + 's'
                });
                document.body.appendChild(p);
            }
        });
    </script>
</body>
</html>