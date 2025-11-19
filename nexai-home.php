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
    <title>Nexai</title>
    <link rel="icon" href="https://imagizer.imageshack.com/img923/3161/sxavmO.png" type="image/png">
    <style>
        @import url('https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.0.3/Vazirmatn-font-face.css');
        body {
            margin: 0;
            overflow: hidden;
            background-color: #0c011a;
            font-family: 'Vazirmatn', sans-serif;
            color: #f0e6ff;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        canvas#background-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .logo-container {
            margin-bottom: 60px;
            position: relative;
        }
        .logo {
            max-width: 180px;
            display: block;
            border-radius: 25px;
            border: 2px solid rgba(200, 122, 255, 0.8);
            box-shadow: 0 0 25px rgba(160, 50, 255, 0.7),
                        inset 0 0 15px rgba(160, 50, 255, 0.4);
            animation: pulse-glow 2s infinite alternate;
        }
        @keyframes pulse-glow {
            from {
                box-shadow: 0 0 25px rgba(160, 50, 255, 0.6),
                            inset 0 0 15px rgba(160, 50, 255, 0.3);
                border-color: rgba(200, 122, 255, 0.7);
            }
            to {
                box-shadow: 0 0 40px rgba(220, 150, 255, 0.9),
                            inset 0 0 20px rgba(220, 150, 255, 0.5);
                border-color: rgba(220, 150, 255, 1);
            }
        }
        .icon-wrapper {
            display: flex;
            justify-content: center;
            gap: 70px;
        }
        .icon-link {
            text-decoration: none;
            color: #f0e6ff;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: transform 0.3s ease;
        }
        .icon-link:hover {
            transform: translateY(-10px);
        }
        .icon {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: radial-gradient(circle, #2a0a4a, #1a062f);
            border: 2px solid #8a2be2;
            box-shadow: 0 0 15px rgba(138, 43, 226, 0.5);
            transition: all 0.3s ease-out;
            cursor: pointer;
        }
        .icon-link:hover .icon {
            background: radial-gradient(circle, #4d128a, #360e63);
            border-color: #c37aff;
            box-shadow: 0 0 25px rgba(195, 122, 255, 0.8);
        }
        .icon svg {
            width: 55px;
            height: 55px;
            fill: #e6d4ff;
            transition: all 0.3s ease;
        }
        .icon-link:hover .icon svg {
            fill: #fff;
            transform: scale(1.05);
            filter: drop-shadow(0 0 5px rgba(255, 255, 255, 0.7));
        }
        .icon-label {
            margin-top: 15px;
            font-size: 1.1rem;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .icon-link:hover .icon-label {
            color: #d8aaff;
        }
        .back-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #bb86fc;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>
    <a href="dashboard.php" class="back-btn">بازگشت</a>
    <canvas id="background-canvas"></canvas>

    <div class="logo-container">
        <img src="https://imagizer.imageshack.com/img923/3161/sxavmO.png" alt="لوگو" class="logo">
    </div>

<div class="icon-Brain">
    <a href="nexai-image.php" class="icon-link" title="چت با Nexai">
        <div class="icon">
            <!-- این src رو بعد از اینکه عکس رو آپلود کردی عوض کن -->
            <img src="./nima.png" 
                 alt="چت با Nexai" 
                 style="width: 100%; height: 100%; object-fit: contain;">
        </div>
        <div class="icon-label">چت با Nexai</div>
    </a>
</div>

        <!-- <a href="chat.html" class="icon-link" title="چت با هوش مصنوعی">
            <div class="icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12,2A10,10,0,1,0,22,12,10,10,0,0,0,12,2Zm0,18a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z"/>
                    <path d="M15.71,8.29a1,1,0,0,0-1.42,0L12,10.59,9.71,8.29A1,1,0,0,0,8.29,9.71L10.59,12,8.29,14.29a1,1,0,1,0,1.42,1.42L12,13.41l2.29,2.29a1,1,0,0,0,1.42-1.42L13.41,12l2.29-2.29A1,1,0,0,0,15.71,8.29Z"/>
                </svg>
            </div>
            <div class="icon-label">چت با AI</div>
        </a>
    </div> -->

    <script>
        const canvas = document.getElementById('background-canvas');
        const ctx = canvas.getContext('2d');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        let particlesArray;
        let mouse = {
            x: null,
            y: null,
            radius: (canvas.height / 90) * (canvas.width / 90)
        };

        window.addEventListener('mousemove', function(event) {
            mouse.x = event.x;
            mouse.y = event.y;
        });

        window.addEventListener('mouseout', function() {
            mouse.x = undefined;
            mouse.y = undefined;
        });

        class Particle {
            constructor(x, y, directionX, directionY, size, color) {
                this.x = x;
                this.y = y;
                this.directionX = directionX;
                this.directionY = directionY;
                this.size = size;
                this.color = color;
            }
            draw() {
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2, false);
                ctx.fillStyle = this.color;
                ctx.fill();
            }
            update() {
                if (this.x > canvas.width || this.x < 0) this.directionX = -this.directionX;
                if (this.y > canvas.height || this.y < 0) this.directionY = -this.directionY;

                let dx = mouse.x - this.x;
                let dy = mouse.y - this.y;
                let distance = Math.sqrt(dx * dx + dy * dy);
                if (distance < mouse.radius + this.size) {
                    if (mouse.x < this.x && this.x < canvas.width - this.size * 10) this.x += 3;
                    if (mouse.x > this.x && this.x > this.size * 10) this.x -= 3;
                    if (mouse.y < this.y && this.y < canvas.height - this.size * 10) this.y += 3;
                    if (mouse.y > this.y && this.y > this.size * 10) this.y -= 3;
                }
                this.x += this.directionX;
                this.y += this.directionY;
                this.draw();
            }
        }

        function init() {
            particlesArray = [];
            const colorPalette = ['#8A2BE2', '#9400D3', '#DA70D6', '#BA55D3', '#FF00FF'];
            let numberOfParticles = (canvas.height * canvas.width) / 9000;
            for (let i = 0; i < numberOfParticles; i++) {
                let size = (Math.random() * 2.5) + 1;
                let x = (Math.random() * ((innerWidth - size * 2) - (size * 2)) + size * 2);
                let y = (Math.random() * ((innerHeight - size * 2) - (size * 2)) + size * 2);
                let directionX = (Math.random() * 0.4) - 0.2;
                let directionY = (Math.random() * 0.4) - 0.2;
                let color = colorPalette[Math.floor(Math.random() * colorPalette.length)];
                particlesArray.push(new Particle(x, y, directionX, directionY, size, color));
            }
        }

        function connect() {
            let opacityValue = 1;
            for (let a = 0; a < particlesArray.length; a++) {
                for (let b = a; b < particlesArray.length; b++) {
                    let distance = ((particlesArray[a].x - particlesArray[b].x) * (particlesArray[a].x - particlesArray[b].x)) +
                                 ((particlesArray[a].y - particlesArray[b].y) * (particlesArray[a].y - particlesArray[b].y));
                    if (distance < (canvas.width / 7) * (canvas.height / 7)) {
                        opacityValue = 1 - (distance / 20000);
                        let dx = mouse.x - particlesArray[a].x;
                        let dy = mouse.y - particlesArray[a].y;
                        let mouseDistance = Math.sqrt(dx*dx + dy*dy);
                        ctx.strokeStyle = mouseDistance < mouse.radius ? `rgba(255, 215, 255, ${opacityValue})` : `rgba(171, 71, 222, ${opacityValue})`;
                        ctx.lineWidth = 1;
                        ctx.beginPath();
                        ctx.moveTo(particlesArray[a].x, particlesArray[a].y);
                        ctx.lineTo(particlesArray[b].x, particlesArray[b].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            requestAnimationFrame(animate);
            ctx.clearRect(0, 0, innerWidth, innerHeight);
            for (let i = 0; i < particlesArray.length; i++) {
                particlesArray[i].update();
            }
            connect();
        }

        window.addEventListener('resize', function() {
            canvas.width = innerWidth;
            canvas.height = innerHeight;
            mouse.radius = ((canvas.height / 90) * (canvas.height / 90));
            init();
        });

        init();
        animate();
    </script>
</body>
</html>