<?php 
require_once 'connect.php';

$connect = new ClsConnect();
$pdo = $connect->getConnection();

// Récupérer $id_contrat depuis une requête GET, avec une valeur par défaut null
$id_contrat = isset($_GET['id_contrat']) ? $_GET['id_contrat'] : null;
$contraT = $connect->getContrat($id_contrat);
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
                    <th>تاريخ المطلب</th>
                    <th>عدد مطلب التحرير</th>
                    <th>عدد العقد</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (is_array($contraT) && !empty($contraT)) { ?>
                <?php foreach ($contraT as $cont) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cont['date_demande'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($cont['id_demande'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($cont['id_contrat'] ?? ''); ?></td>
                        <td>
                            <!-- Commenté pour éviter les erreurs si non implémenté -->
                            <!-- <a href="Traitement.php?id_demande=<?php echo urlencode($cont['id_demande'] ?? ''); ?>&num_recu=<?php echo urlencode($cont['num_recu'] ?? ''); ?>">معالجة</a> -->
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