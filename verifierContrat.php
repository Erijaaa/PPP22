<?php
require_once 'connect.php';
$connect = new ClsConnect();
$pdo = $connect->getConnection();

$resultat = $connect->insertTextRefusR($pdo);

if ($resultat) {
    echo "<script>alert('".$resultat."');</script>";
}

if (isset($_POST['ajouter_ligne'])) {
    require_once 'connect.php'; // si tu as une classe ou connexion PDO
    $pdo = (new ClsConnect())->getConnection(); // selon ta méthode de connexion
    $id_demande = $_POST['id_demande']; // tu dois passer cet ID depuis ton formulaire
    insererNouvelleLigne($pdo, $id_demande);
}

?>



<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد العقد</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #163e60 0%, #163e60 100%);
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
            color: #072b4a;
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
            background: linear-gradient(135deg, #456886 0%, #456886 100%);
        }

        .button-1:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(67, 206, 162, 0.4);
        }

        .button-2 {
            background: linear-gradient(135deg, #456886 0%, #456886 100%);
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

        /* Styles pour le modal */
        .modal-overlay {
            display: none; /* Caché par défaut */
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            text-align: center;
            min-width: 400px;
            max-width: 500px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
        }

        .close-btn {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            font-size: 24px;
            color: #456886;
            cursor: pointer;
        }

        .modal-header {
            font-size: 2rem;
            font-weight: 700;
            color: #456886;
            margin-bottom: 20px;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .textarea-group textarea {
            width: 100%;
            height: 100px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            resize: none;
            font-size: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-primary {
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
            background: linear-gradient(135deg, #fa709a 0%, #456886 100%);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(250, 112, 154, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="title" href="listeContratRedacteur.php"> تأكيد العقد</h1>
        <form method="post" action="listeContratRedacteur.php">
            <input type="hidden" name="id_demande" value="123"> <!-- cet ID doit venir dynamiquement -->
            <button type="submit" name="ajouter_ligne" class="button button-1">📥 حفظ العقد</button>
        </form>
        <a href="#" class="button button-2" onclick="openModal()"> 🚫 نص الاعتراض</a>
        <a class="button button-1" href="logout.php" data-section="logout">❌ تسجيل الخروج</a>

        <div class="modal-overlay" id="modalOverlay" style="display: none;">
            <form method="POST" action="">
                <div class="modal">
                    <button class="close-btn" type="button" onclick="closeModal()">×</button>
                    <div class="modal-header">نص الاعتراض</div>
                    <div class="modal-body">
                        <div class="textarea-group">
                            <textarea id="objectionText" name="text_refus" placeholder="اكتب نص الاعتراض هنا..." required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn-primary" name="submit">ارسال النص</button>
                </div>
            </form>
        </div>
    </div>
        <script>
            function openModal() {
                document.getElementById('modalOverlay').style.display = 'flex';
            }

            function closeModal() {
                document.getElementById('modalOverlay').style.display = 'none';
                document.getElementById('objectionText').value = '';
            }

            // Effet ripple sur les boutons
            document.querySelectorAll('.button, .btn-primary').forEach(button => {
                button.addEventListener('click', function(e) {
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