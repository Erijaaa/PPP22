<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>واجهة المحرر</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #8bafcd 0%, #8bafcd 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 60px 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            min-width: 400px;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .title {
            font-size: 3rem;
            font-weight: 700;
            color: #163e60;
            margin-bottom: 50px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: 2px;
        }

        .button {
            display: block;
            width: 100%;
            padding: 18px 30px;
            margin: 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            overflow: hidden;
        }

        .button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }

        .button:hover::before {
            left: 100%;
        }

        .button-1 {
            background: linear-gradient(135deg, #8bafcd 0%, #8bafcd 100%);
        }

        .button-1:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(67, 206, 162, 0.4);
        }

        .button-2 {
            background: linear-gradient(135deg, #8bafcd 0%, #8bafcd 100%);
        }

        .button-2:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(250, 112, 154, 0.4);
        }

        .button:active {
            transform: translateY(0);
        }

        @media (max-width: 600px) {
            .container {
                padding: 40px 30px;
                min-width: 300px;
            }
            
            .title {
                font-size: 2.5rem;
                margin-bottom: 40px;
            }
            
            .button {
                font-size: 1.2rem;
                padding: 16px 25px;
            }
        }

        /* Animation d'entrée */
        .container {
            animation: slideIn 0.8s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .button {
            animation: fadeInUp 0.6s ease-out forwards;
            opacity: 0;
        }

        .button:nth-child(2) {
            animation-delay: 0.2s;
        }

        .button:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title">واجهة المحرر</h1>
        <a href="consultation.php" class="button button-1"> 📋 قائمة المطالب </a>
        <a href="listeContratRedacteur.php" class="button button-2"> ⚖️ قائمة العقود </a>
        <a class="button button-2" href="logout.php" data-section="logout">❌ تسجيل الخروج</a>
    </div>
    <script>
        document.querySelectorAll('.button').forEach(button => {
            button.addEventListener('click', function(e) {
                // Créer un effet de ripple
                const ripple = document.createElement('span');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(255, 255, 255, 0.6)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'ripple 0.6s linear';
                ripple.style.pointerEvents = 'none';
                
                this.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    </script>
</body>
</html>