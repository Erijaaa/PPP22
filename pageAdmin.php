<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("connect.php"); 
$db = new ClsConnect();
$pdo = $db->getConnection();

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    error_log("POST data: " . print_r($_POST, true));
    $result = $db->updateUser($pdo);
    echo "<div style='padding: 10px; font-weight: bold;'>";
    if ($result === "✅ Utilisateur mis à jour avec succès") {
        echo "<span style='color: green;'>✅ تم تحديث المستخدم بنجاح</span>";
    } else {
        echo "<span style='color: red;'>❌ خطأ: $result</span>";
    }
    echo "</div>";
}

// Handle add user form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $result = $db->ajouterUser($pdo);
    echo "<div style='padding: 10px; font-weight: bold;'>";
    if ($result === "✅ Utilisateur inséré avec succès") {
        echo "<span style='color: green;'>✅ تم إضافة المستخدم بنجاح</span>";
    } else {
        echo "<span style='color: red;'>❌ خطأ: $result</span>";
    }
    echo "</div>";
}


// Fetch all users
$sql = "SELECT * FROM acteurs";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم</title>
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
        .actions a {
            margin: 0 5px;
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            color: white;
        }
        .edit-btn {
            background-color: #f39c12;
        }
        .delete-btn {
            background-color: #e74c3c;
            border: none;
            color: white;
            padding: 5px 12px;
            cursor: pointer;
            border-radius: 5px;
            display: inline-block;
        }
        .edit-btn {
            background-color: #ffc107;
            border: none;
            color: white;
            padding: 5px 12px;
            cursor: pointer;
            border-radius: 5px;
        }
        .save-btn {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 6px 12px;
            margin-right: 10px;
            border-radius: 5px;
        }
        .cancel-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
        }
        .edit-user-form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .edit-user-form .form-group {
            display: flex;
            flex-direction: column;
            flex: 1 1 200px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h2>لوحة التحكم</h2>
        <ul class="sidebar-menu">
            <li><a href="pageAdmin.php" class="menu-item active" data-section="agents">👥 إدارة الوكلاء</a></li>
            <li><a href="listeDemAdmin.php" class="menu-item" data-section="requests">📋 قائمة المطالب</a></li>
            <li><a href="listeContratAdmin.php" class="menu-item" data-section="contracts">📄 قائمة العقود</a></li>
            <li><a href="listeTextRefus.php" class="menu-item" data-section="textRefus">⚠️ قائمة نصوص الاعتراض</a></li>
            <li><a href="logout.php" class="menu-item logout" data-section="logout">❌ تسجيل الخروج</a></li>

        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="header">
            <h1>👥 إدارة الوكلاء</h1>
        </div>

        <!-- Agents Management Section -->
        <div id="agents-content" class="content-section active">
           

            <!-- Form -->
            <div class="form-container">
                <form id="agentForm" method="POST" action="">
                    <div class="form-group" style="display: flex; align-items: center">
                        <label for="post">نوع الحساب</label>
                        <select name="post" required>
                            <option value="">-- --</option>
                            <option value="1">تحرير العقود </option>
                            <option value="2">مصادقة على العقود</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="prenom_acteur">الاسم </label>
                        <input type="text" id="prenom_acteur" name="prenom_acteur" required>
                    </div>
                    <div class="form-group">
                        <label for="nom_acteur">اللقب </label>
                        <input type="text" id="nom_acteur" name="nom_acteur" required>
                    </div>
                    <div class="form-group">
                        <label for="cin_acteur">رقم التعريف</label>
                        <input type="text" id="cin_acteur" name="cin_acteur" required>
                    </div>
                    <div class="form-group">
                        <label for="cin_acteur">تاريخ الولادة </label>
                        <input type="date" id="date_naissance_acteur" name="date_naissance_acteur" required>
                    </div>
                    <div class="form-group">
                        <label for="email">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="adresse">العنوان</label>
                        <input type="text" id="adresse" name="adresse" required>
                    </div>
                    <div class="form-group">
                        <label for="telephone">رقم الهاتف</label>
                        <input type="text" id="telephone" name="telephone" required>
                    </div>
                    <div class="form-group">
                        <label for="password">كلمة المرور</label>
                        <input type="text" id="password" name="password" required>
                    </div>
                    <div class="form-actions">
                        <button type="submit" name="submit" class="btn btn-primary">إضافة</button>
                        <button type="button" class="btn btn-secondary" onclick="clearForm()">إلغاء</button>
                    </div>
                </form>
            </div>

            <!-- Users Table -->
            <h2 style="text-align:center;">قائمة المستخدمين</h2>
            <?php if (!empty($users)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>الاسم </th>
                            <th> اللقب </th>
                            <th>رقم التعريف</th>
                            <th>البريد الإلكتروني</th>
                            <th>العنوان</th>
                            <th>الوظيفة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($user['prenom_acteur'] ?? '') ?></strong></td>
                                <td><strong><?= htmlspecialchars($user['nom_acteur'] ?? '') ?></strong></td>
                                <td><?= htmlspecialchars($user['cin_acteur'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['adresse'] ?? '') ?></td>
                                <td><?= htmlspecialchars($user['post']) ?></td>
                                <td class="actions">
                                    <button class="edit-btn" onclick="showEditForm(this, <?= htmlspecialchars(json_encode($user)) ?>)">تعديل</button>
                                    <?php if (($user['cin_acteur'] ?? null) !== ($_SESSION['cin_acteur'] ?? null)): ?>
                                        <button class="delete-btn" onclick="deleteRow(this); return confirm('هل أنت متأكد من حذف هذا المستخدم من القائمة؟')">حذف</button>
                                    <?php endif; ?>
                                </td> 
                            </tr>
                            <!-- Hidden edit form row -->
                            <tr class="edit-form-row" style="display: none;">
                                <td colspan="5">
                                    <form class="edit-user-form" method="POST" action="">
                                        <input type="hidden" name="original_cin_acteur" value="<?= htmlspecialchars($user['cin_acteur'] ?? '') ?>">
                                        <input type="hidden" name="update" value="1">
                                        <div class="form-group">
                                            <label>الاسم </label>
                                            <input type="text" name="prenom_acteur" value="<?= htmlspecialchars($user['prenom_acteur'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>اللقب </label>
                                            <input type="text" name="nom_acteur" value="<?= htmlspecialchars($user['nom_acteur'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>رقم التعريف</label>
                                            <input type="text" name="cin_acteur" value="<?= htmlspecialchars($user['cin_acteur'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>البريد الإلكتروني</label>
                                            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label> العنوان</label>
                                            <input type="text" name="adresse" value="<?= htmlspecialchars($user['adresse'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>رقم الهاتف</label>
                                            <input type="text" name="telephone" value="<?= htmlspecialchars($user['telephone'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>تاريخ الولادة</label>
                                            <input type="date" name="date_naissance_acteur" value="<?= htmlspecialchars($user['date_naissance_acteur'] ?? '') ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label>كلمة المرور</label>
                                            <input type="text" name="password" placeholder="أدخل كلمة مرور جديدة أو اتركها فارغة">
                                        </div>
                                        <div class="form-group">
                                            <label>عدد الصلاحية</label>
                                            <select name="post" required>
                                                <option value="">-- اختر المنصب --</option>
                                                <option value="1" <?= ($user['post'] ?? '') == '1' ? 'selected' : '' ?>>1</option>
                                                <option value="2" <?= ($user['post'] ?? '') == '2' ? 'selected' : '' ?>>2</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="save-btn">تم الحفظ</button>
                                        <button type="button" class="cancel-btn" onclick="hideEditForm(this)">إلغاء</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align:center;">لا يوجد مستخدمون حاليا.</p>
            <?php endif; ?>
        </div>
    </div>
    <div>
        <!-- Contracts Management Section -->
        <div id="contracts-content" class="content-section">
            <h2> قائمة العقود</h2>
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
                    <?php if (is_array($resultats) && !empty($resultats)) { ?>
                        <?php foreach ($resultats as $resultat) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($resultat['date_contrat'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($resultat['id_demande'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($resultat['num_contrat'] ?? ''); ?></td>
                                <td class="<?php echo getStatusClass($resultat['etat_contrat'] ?? 0); ?>">
                                    <?php echo getStatusText($resultat['etat_contrat'] ?? 0); ?>
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
        <!-- Contracts Management Section -->
        <div id="contracts-content" class="content-section">
            <h2>قائمة العقود</h2>
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
                    <?php if (is_array($resultats) && !empty($resultats)) { ?>
                        <?php foreach ($resultats as $resultat) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($resultat['date_contrat'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($resultat['id_demande'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($resultat['num_contrat'] ?? ''); ?></td>
                                <td class="<?php echo getStatusClass($resultat['etat_contrat'] ?? 0); ?>">
                                    <?php echo getStatusText($resultat['etat_contrat'] ?? 0); ?>
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
    </div>
    <script src="script/script.js"></script>
    <script>
    function showEditForm(button, userData) {
        document.querySelectorAll('.edit-form-row').forEach(row => {
            row.style.display = 'none';
        });
        const row = button.closest('tr');
        const formRow = row.nextElementSibling;
        formRow.style.display = 'table-row';
        const form = formRow.querySelector('form');
        form.nom_prenom_user.value = userData.nom_prenom_user || '';
        form.cin_user.value = userData.cin_user || '';
        form.email_user.value = userData.email_user || '';
        form.adresse_user.value = userData.adresse_user || '';
        form.telephone_user.value = userData.telephone_user || '';
        form.date_naissance_user.value = userData.date_naissance_user || '';
        form.password_user.value = '';
        form.post.value = userData.post || '';
    }

    function hideEditForm(button) {
        const formRow = button.closest('.edit-form-row');
        formRow.style.display = 'none';
    }

    function clearForm() {
        document.getElementById('agentForm').reset();
    }

    function deleteRow(button) {
        if (confirm('هل أنت متأكد من حذف هذا المستخدم من القائمة؟')) {
            const row = button.closest('tr');
            const nextRow = row.nextElementSibling; // The edit form row
            row.remove();
            if (nextRow && nextRow.classList.contains('edit-form-row')) {
                nextRow.remove(); // Remove the associated edit form row
            }
        }
    }
    </script>
</body>
</html>