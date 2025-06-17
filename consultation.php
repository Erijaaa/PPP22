<?php 
require_once 'connect.php';

$connect = new ClsConnect();
$pdo = $connect->getConnection();

$type_demande = 14;
$data=$connect->traitResult($type_demande);


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الطلبات</title>
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
            <h2>طلبات نوع الخدمة <?php echo $type_demande; ?></h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>تاريخ المطلب</th>
                    <th>عدد مطلب التحرير</th>
                    <th>عدد الوصل</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if (is_array($data)) { ?>
                <?php foreach ($data as $demande) { ?>
                    <tr>
                        <td><?php echo htmlspecialchars($demande['date_demande']); ?></td>
                        <td><?php echo htmlspecialchars($demande['id_demande']); ?></td>
                        <td><?php echo htmlspecialchars($demande['num_recu']); ?></td>
                        <td>
                            <a href="Traitement.php?id_demande=<?php echo urlencode($demande['id_demande']); ?>&num_recu=<?php echo urlencode($demande['num_recu']); ?>">معالجة</a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
        </table>
    </div>

    <div class="logout-container">
        <a href="logout.php" class="logout-button">تسجيل خروج</a>
    </div>
</body>
</html>