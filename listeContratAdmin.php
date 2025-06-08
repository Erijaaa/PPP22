<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'connect.php';
$connect = new ClsConnect();
$pdo = $connect->getConnection();
$resultats = $connect->traitContratsss();
// Debug de la connexion
try {
    $test = $pdo->query('SELECT 1');
    error_log("Connexion à la base de données réussie");
} catch (PDOException $e) {
    error_log("Erreur de connexion : " . $e->getMessage());
}


// Test de la table T_demande
try {
    $test = $pdo->query('SELECT COUNT(*) FROM "T_demande"');
    $count = $test->fetchColumn();
    error_log("Nombre de contrats dans la table : " . $count);
} catch (PDOException $e) {
    error_log("Erreur lors de l'accès à la table contrats : " . $e->getMessage());
}

// Récupérer le critère de tri
$sortBy = isset($_GET['sort']) ? $_GET['sort'] : '';

try {
    // Récupération des demandes avec tri
    $query = 'SELECT * FROM "contrats"';
    
    // Ajouter la clause ORDER BY selon le critère
    switch($sortBy) {
        case 'date_contrat':
            $query .= ' ORDER BY date_contrat DESC';
            break;
        case 'etat_contrat':
            $query .= ' ORDER BY CASE 
                        WHEN etat_contrat = 1 THEN 1 
                        WHEN etat_contrat = 0 THEN 1 
                        WHEN etat_contrat = -1 THEN -1 
                        ELSE 1 END';
            break;
    }
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $dem = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    echo "Erreur : " . $e->getMessage();
    die();
}

// Fonctions pour l'état
function getStatusClass($etat_contrat) {
    switch ($etat_contrat) {
        case 1:
            return 'status-approved';
        case -1:
            return 'status-rejected';
        default:
            return 'status-pending';
    }
}

function getStatusText($etat_contrat) {
    switch ($etat_contrat) {
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
            <label for="filter">قائمة العقود حسب:</label>
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
                </tr>
            </thead>
            <tbody>
                <?php if (is_array($resultats)) : ?>
                    <?php foreach ($resultats as $resultat) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($resultat['date_contrat'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($resultat['id_demande'] ?? ''); ?></td>
                            <td><?php echo htmlspecialchars($resultat['num_contrat'] ?? ''); ?></td>
                            <td class="<?php echo getStatusClass($resultat['etat_contrat'] ?? 0); ?>">
                                <?php echo getStatusText($resultat['etat_contrat'] ?? 0); ?>
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
</html>