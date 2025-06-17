<?php 
require_once 'connect.php';

// Create an instance of ClsConnect
$connect = new ClsConnect();
$pdo = $connect->getConnection();

// Call the traitContrat method with default or specific parameters
$resultats = $connect->traitContrat(1, 0); // Use default values: etat_demande = 1, etat_contrat = 0

// Function to get the CSS class for the status
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

// Function to display the status text in Arabic
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
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>قائمة العقود</title>
    <link rel="stylesheet" href="css/consultation.css">
    <style>
        .logout-container {
        text-align: center;
        }

        .logout-button {
        display: inline-block;
        background-color: #dc3545; /* rouge */
        color: white;
        text-decoration: none;
        padding: 12px 25px;
        border-radius: 5px;
        font-size: 18px;
        transition: background-color 0.3s ease;
        }

        .logout-button:hover {
        background-color: #c82333; /* plus foncé au survol */
        }

    </style>
</head> 
<body>
    <div class="container">
        <div class="header">
            <h2>قائمة العقود</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>تاريخ التحرير</th>
                    <th>عدد مطلب التحرير</th>
                    <th>عدد العقد</th>
                    <th>الحالة</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (is_array($resultats) && !empty($resultats)) { ?>
                <?php foreach ($resultats as $resultat) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($resultat['date_contrat'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($resultat['id_demande'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($resultat['num_contrat'] ?? ''); ?></td>
                        <td class="<?php echo getStatusClass($resultat['etat_contrat'] ?? 0); ?>">
                            <?php echo getStatusText($resultat['etat_contrat'] ?? 0); ?>
                        </td>
                        <td>
                            <a href="pageValidationValideur.php?id_demande=<?php echo urlencode($resultat['id_demande']); ?>">تأكيد</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="5">لا توجد عقود متاحة</td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
    <div class="logout-container">
        <a href="logout.php" class="logout-button">تسجيل خروج</a>
    </div>
</body>
</html>