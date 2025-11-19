<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexAI - هوش مصنوعی همه‌کاره</title>
    <link rel="icon" href="https://imagizer.imageshack.com/img923/3161/sxavmO.png" type="image/png">
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Vazirmatn', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #000000, #1a0033, #2c003e, #8a2be2);
            background-size: 400% 400%;
            animation: gradientFlow 15s ease infinite;
            color: #e0d4ff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        @keyframes gradientFlow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .container {
            max-width: 900px;
            width: 100%;
            background: rgba(18, 18, 35, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 32px;
            padding: 50px 40px;
            box-shadow: 0 30px 60px rgba(138, 43, 226, 0.35);
            text-align: right;
            position: relative;
            overflow: hidden;
        }
        .logo {
            width: 160px;
            height: auto;
            display: block;
            margin: 0 auto 30px;
            filter: drop-shadow(0 0 20px rgba(138, 43, 226, 0.8));
            animation: logoGlow 2s ease-in-out infinite alternate;
        }
        @keyframes logoGlow {
            from { filter: drop-shadow(0 0 15px rgba(138, 43, 226, 0.6)); }
            to { filter: drop-shadow(0 0 25px rgba(138, 43, 226, 0.9)); }
        }
        .title {
            font-size: 2.6em;
            text-align: center;
            background: linear-gradient(45deg, #8a2be2, #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 25px;
        }
        .page-content {
            font-size: 1.15em;
            line-height: 1.9;
            margin-bottom: 35px;
            opacity: 0;
            transition: opacity 1s ease;
        }
        .page-content.visible { opacity: 1; }
        .navigation {
            display: flex;
            justify-content: space-between;
            margin-top: 25px;
            gap: 15px;
        }
        .nav-btn {
            flex: 1;
            padding: 14px 28px;
            font-size: 1.05em;
            color: white;
            background: linear-gradient(45deg, #8a2be2, #2c003e);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s;
        }
        .nav-btn:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px rgba(138, 43, 226, 0.5);
        }
        .nav-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        .enter-btn {
            padding: 16px 32px;
            font-size: 1.15em;
            color: white;
            background: linear-gradient(45deg, #2c003e, #8a2be2);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.4s;
            margin-top: 25px;
            display: block;
            width: 100%;
        }
        .enter-btn:hover {
            transform: scale(1.03);
            box-shadow: 0 12px 24px rgba(138, 43, 226, 0.5);
        }
        @media (max-width: 768px) {
            .container { padding: 40px 30px; border-radius: 24px; }
            .title { font-size: 2.2em; }
            .page-content { font-size: 1.05em; line-height: 1.8; }
            .logo { width: 140px; }
            .navigation { flex-direction: column; gap: 12px; }
            .nav-btn { padding: 12px 24px; font-size: 1em; }
            .enter-btn { padding: 14px 28px; font-size: 1.05em; }
        }
        @media (max-width: 480px) {
            body { padding: 15px; }
            .container { padding: 30px 20px; border-radius: 20px; }
            .title { font-size: 1.8em; }
            .page-content { font-size: 1em; line-height: 1.7; }
            .logo { width: 120px; margin-bottom: 20px; }
            .navigation { gap: 10px; }
            .nav-btn { padding: 10px 20px; font-size: 0.95em; }
            .enter-btn { padding: 12px 24px; font-size: 1em; }
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="https://imagizer.imageshack.com/img923/3161/sxavmO.png" alt="NexAI Logo" class="logo">
        <h1 class="title">NexAI</h1>

        <div id="page1" class="page-content visible">
            <p>NexAI یک هوش مصنوعی کامل و همه‌کاره است که توسط نیما حسین‌زاده ساخته شده—مثل یک همکار خلاق که همه کار می‌کنه، از کد نویسی و طراحی گرفته تا ایده‌پردازی و ساخت پروژه‌های منحصربه‌فرد.</p>
            <p>فعلاً ساخت ویدیو رو پشتیبانی نمی‌کنه، اما ساخت تصویر رو عالی انجام می‌ده—از طریق بخش اختصاصی.</p>
            <p>اگر دنبال یک AI هستید که ایده‌هاتون رو زنده کنه، NexAI گزینه ایدئاله.</p>
        </div>
        <div id="page2" class="page-content" style="display: none;">
            <p>چطور NexAI کار می‌کنه؟ این AI همه‌کاره است و می‌تونه به سوالات پاسخ بده، کد بنویسه، طراحی کنه، انیمیشن بسازه و حتی گیم‌دیزاین کنه.</p>
            <p>هدف اصلیش مثل OpenAI: ایجاد یک ابزار که مردم رو متصل، خوشحال و متعجب کنه.</p>
            <p>ساخته‌شده با HTML/CSS/JS ساده، سبک و قابل گسترش.</p>
        </div>
        <div id="page3" class="page-content" style="display: none;">
            <p>آینده NexAI روشن و پر از پتانسیله: نیما می‌خواد این AI رو به یک شریک واقعی پروژه تبدیل کنه.</p>
            <p>اگر آماده‌اید وارد دنیای NexAI بشید، از بخش ساخت تصویر شروع کنید.</p>
            <p>حالا وقتشه شروع کنیم—ورود کنید و خلاقیت رو زنده کنید.</p>
            <button class="enter-btn" onclick="window.location.href='nexai-home.php'">ورود به NexAI</button>
        </div>

        <div class="navigation">
            <button id="prevBtn" class="nav-btn" disabled>صفحه قبلی</button>
            <button id="nextBtn" class="nav-btn">صفحه بعدی</button>
        </div>
    </div>

    <script>
        const pages = [document.getElementById('page1'), document.getElementById('page2'), document.getElementById('page3')];
        let currentPage = 0;
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');

        function showPage(index) {
            pages[currentPage].classList.remove('visible');
            setTimeout(() => {
                pages[currentPage].style.display = 'none';
                currentPage = index;
                pages[currentPage].style.display = 'block';
                setTimeout(() => {
                    pages[currentPage].classList.add('visible');
                }, 50);
                updateButtons();
            }, 1000);
        }

        function updateButtons() {
            prevBtn.disabled = currentPage === 0;
            nextBtn.disabled = currentPage === pages.length - 1;
            nextBtn.style.display = currentPage === pages.length - 1 ? 'none' : 'block';
        }

        prevBtn.addEventListener('click', () => {
            if (currentPage > 0) showPage(currentPage - 1);
        });

        nextBtn.addEventListener('click', () => {
            if (currentPage < pages.length - 1) showPage(currentPage + 1);
        });

        updateButtons();
    </script>
</body>
</html>