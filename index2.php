<?php
/*require_once 'connect.php';
$cls = new ClsConnect();

$user = $cls->verifierUtilisateur($_POST['cin'], $_POST['password']);
if ($user) {
    $cls->demarrerSessionUtilisateur($user);
    $cls->redirigerParRole($_SESSION['userAuth']['role']);
} */


?>


<!DOCTYPE html>
<html lang="ar" dir="rtl">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/style_index.css" />
    <style>
      .logo-section {
        justify-self: right;
        order: 5;
        font-size: 55px;
      }

      .onpf-logo {
        width: 700px;
        height: auto;
      }
      .form-title {
        font-size: 60px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 80px;
        color: #11500b;
      }

      .logo-container {
        text-align: right;
        width: 700px;
        height: auto;
      }
      .connect-btn {
            position: relative;
            padding: 16px 40px;
            font-size: 18px;
            font-weight: 600;
            color: white;
            background: linear-gradient(135deg, #1d6117, #1d6117);
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 8px 25px rgba(238, 90, 36, 0.3);
            transition: all 0.3s cubic-bezier(0.23, 1, 0.320, 1);
            overflow: hidden;
            outline: none;
        }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="header">
        <div class="logo-container">
          <div class="header-text">
            <img src="media/ministere.png" alt="" />
          </div>
        </div>
      </div>
      <div class="main-content">
        <form action="verifier_email.php" method="post">
          <div class="login-form">
            <h1 class="form-title">  المنظومة المعلوماتية لتحرير العقود</h1>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"></label>نوع الحساب</label>
              <select name="post" class="form-input" dir="rtl" required>
                <option value="">-- --</option>
                <option value="0">إدارة المنظومة</option>
                <option value="1">تحرير العقود</option>
                <option value="2">مصادقة على العقود</option>
              </select>
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"
                >الإدارة الجهوية</label>
              <select name="region" class="form-input" dir="rtl" required>
                <option value="">-- --</option>
                <option value="Tunis">تونس</option>
                <option value="Beja">باجة</option>
                <option value="Jendouba">جندوبة</option>
                <option value="Ariana">أريانة</option>
                <option value="Ben Arous">بن عروس</option>
                <option value="Manouba">منوبة</option>
                <option value="Nabeul_1">نابل_1</option>
                <option value="Nabeul_2">نابل_2</option>
                <option value="Bizerte">بنزرت</option>
                <option value="Beja">باجة</option>
                <option value="Jendouba">جندوبة</option>
                <option value="Kef">الكاف</option>
                <option value="Siliana">سليانة</option>
                <option value="Sousse">سوسة</option>
                <option value="Monastir">المنستير</option>
                <option value="Mahdia">المهدية</option>
                <option value="Sfax">صفاقس</option>
                <option value="Kairouan">القيروان</option>
                <option value="Kasserine">القصرين</option>
                <option value="Sidi Bouzid">سيدي بوزيد</option>
                <option value="Gabes">قابس</option>
                <option value="Medenine">مدنين</option>
                <option value="Gafsa">قفصة</option>
                <option value="Kebili">قبلي</option>
              </select>
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"
                >رقم التعريف</label
              >
              <input type="text" name="cin_admin" class="form-input" required />
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px">الاسم</label>
              <input type="text" name="nom_admin" class="form-input" required />
            </div>

            <div class="form-group" style="display: flex; align-items: center">
              <label class="form-label" style="margin-right: 10px"
                >كلمة المرور</label
              >
              <input
                type="password"
                name="password"
                class="form-input"
                required
              />
            </div>

            <button type="submit" class="connect-btn">
              <span class="icon">→</span>
              CONNECTER
            </button>
          </div>
        </form>

        <div class="logo-section">
          <img class="onpf-logo" src="media/logo.png" alt="ONPF Logo" />
        </div>
      </div>
    </div>
  </body>
</html>