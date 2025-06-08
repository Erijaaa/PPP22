<?php 
require_once 'connect.php';

// Create an instance of ClsConnect
$connect = new ClsConnect();
$pdo = $connect->getConnection();

// Call the traitContrat method from ClsConnect
$resultats = $connect->traitContrat(1, 0);

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
</head> 
<body>
    <div class="container">
        <div class="header">
            <h2>قائمة العقود</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>تاريخ الإنشاء</th>
                    <th>عدد مطلب التحرير</th>
                    <th>عدد العقد</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
            <?php if (is_array($resultats) && !empty($resultats)) { ?>
                <?php foreach ($resultats as $cont) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cont['date_creation'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($cont['id_demande'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($cont['id_contrat'] ?? ''); ?></td>
                        <td class="<?php echo getStatusClass($cont['etat_contrat'] ?? 0); ?>">
                            <?php echo getStatusText($cont['etat_contrat'] ?? 0); ?>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr><td colspan="4" style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</body>
</html>