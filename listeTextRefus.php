<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'connect.php';
$connect = new ClsConnect();
$pdo = $connect->getConnection();
$rslt = $connect->afficherTextRefus($pdo);

?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة العقود</title>
    <link rel="stylesheet" href="css/pageAdmin.css">
    <style>
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto;
            direction: rtl;
        }
        th, td {
            border: 1px solid #666;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        .no-data {
            text-align: center;
            padding: 20px;
            color: #666;
        }
        .status-pending {
            color: #f39c12;
        }
        .status-approved {
            color: #27ae60;
        }
        .status-rejected {
            color: #e74c3c;
        }
        .btn-modifier {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }
        .btn-modifier:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>لوحة التحكم</h2>
        <ul class="sidebar-menu">
            <li><a href="pageAdmin.php" class="menu-item" data-section="agents">👥 إدارة الوكلاء</a></li>
            <li><a href="listeDemAdmin.php" class="menu-item" data-section="requests">📋 قائمة المطالب</a></li>
            <li><a href="listeContratAdmin.php" class="menu-item" data-section="contracts">📄 قائمة العقود</a></li>
            <li><a href="listeTextRefus.php" class="menu-item active" data-section="textRefus">⚠️ قائمة نصوص الاعتراض</a></li>
            <li><a href="logout.php" class="menu-item logout" data-section="logout">❌ تسجيل الخروج</a></li>

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>قائمة نصوص الاعتراض</h1>
        </div>
        <table class="refus-table">
            <thead>
                <tr>
                    <th scope="col">عدد مطلب التحرير</th>
                    <th scope="col">نص الاعتراض</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($rslt) && is_array($rslt) && !empty($rslt)) : ?>
                    <?php foreach ($rslt as $resultat) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resultat['id_demande'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?php echo htmlspecialchars($resultat['text_refus'] ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2">لا توجد نصوص متاحة</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>

    .refus-table {
        width: 100%;
        border-collapse: collapse;
        direction: rtl; /* Support pour l'écriture de droite à gauche */
        text-align: right;
    }
    .refus-table th, .refus-table td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    .refus-table th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .refus-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    function filterDemandes(value) {
    window.location.href = 'listeTextRefus.php?sort=' + value;
    }
    document.addEventListener('DOMContentLoaded', function () {
        const menuItems = document.querySelectorAll('.sidebar-menu .menu-item');
        const contentSections = document.querySelectorAll('.content-section');

    menuItems.forEach(item => {
        item.addEventListener('click', function (e) {
            if (this.getAttribute('href') === 'listeTextRefus.php') {
                e.preventDefault();
                menuItems.forEach(menu => menu.classList.remove('active'));
                this.classList.add('active');
                contentSections.forEach(section => section.classList.remove('active'));
                const sectionId = this.getAttribute('data-section') + '-content';
                const targetSection = document.getElementById(sectionId);
                if (targetSection) {
                    targetSection.classList.add('active');
                }
            }
            // Sinon, laisser la redirection se produire
        });
    });
});

function filterContrats(value) {
    window.location.href = 'listeTextRefus.php?sort=' + value;
}
</script>
</body>
</html>