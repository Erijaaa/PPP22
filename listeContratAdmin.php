<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'connect.php';
$connect = new ClsConnect();
$pdo = $connect->getConnection();

// Récupérer les contrats
$resultats = $connect->traitContratsss();

// Fonctions pour l'état
function getStatusClass($etat) {
    switch ($etat) {
        case 1:
            return 'status-approved';
        case -1:
            return 'status-rejected';
        default:
            return 'status-pending';
    }
}

function getStatusText($etat) {
    switch ($etat) {
        case 1:
            return 'مقبول';
        case -1:
            return 'مرفوض';
        default:
            return 'في الانتظار';
    }
}
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
            <li><a href="listeContratAdmin.php" class="menu-item active" data-section="contracts">📄 قائمة العقود</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>قائمة العقود</h1>
        </div>
        
        <!-- Filtres -->
        <div class="filters" style="margin: 20px;">
            <label for="filter">قائمة العقود  حسب:</label>
            <select id="filter" onchange="filterContrats(this.value)">
                <option value="">الكل</option>
                <option value="date" <?php echo $sortBy == 'date' ? 'selected' : ''; ?>>التاريخ</option>
                <option value="status" <?php echo $sortBy == 'status' ? 'selected' : ''; ?>>الحالة</option>
            </select>
        </div>

        <!-- Table des demandes -->
        <table>
        <thead>
            <tr>
                <th>تاريخ التحرير</th>
                <th>عدد مطلب التحرير</th>
                <th>عدد العقد</th>
                <th>الحالة</th>
                <th>الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (is_array($resultats) && !empty($resultats)) : ?>
                <?php foreach ($resultats as $resultat) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($resultat['date_contrat'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($resultat['id_demande'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($resultat['num_contrat'] ?? ''); ?></td>
                        <td class="<?php echo getStatusClass($resultat['etat_contrat'] ?? 0); ?>">
                            <?php echo getStatusText($resultat['etat_contrat'] ?? 0); ?>
                        </td>
                        <td>
                            <button onclick="modifierContrat('<?php echo htmlspecialchars($resultat['id_demande'] ?? ''); ?>', '<?php echo htmlspecialchars($resultat['num_contrat'] ?? ''); ?>')" class="btn-modifier">تعديل</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5">لا توجد عقود متاحة</td>
                </tr>
            <?php endif; ?>
        </tbody>
            </table>
        </div>
    </div>        
<script>
document.querySelectorAll('.menu-item').forEach(item => {
    item.addEventListener('click', function(e) {
        e.preventDefault(); 
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const menuItems = document.querySelectorAll('.sidebar-menu .menu-item');
    const contentSections = document.querySelectorAll('.content-section');

    menuItems.forEach(item => {
        item.addEventListener('click', function (e) {
            // Ne pas empêcher la redirection si href n'est pas "#"
            if (this.getAttribute('href') === '#') {
                e.preventDefault();
                // Bascule les sections
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
    window.location.href = 'listeContratAdmin.php?sort=' + value;
}
</script>
</body>
</div>
