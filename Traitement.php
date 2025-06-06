<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("connect.php");
//require_once 'insert_data.php'; 
$db = new ClsConnect();

if (isset($_GET['id_demande']) && isset($_GET['num_recu'])) {
    $id_demande = $_GET['id_demande'];
    $num_recu = $_GET['num_recu'];
    $demande = $db->getDemandeById($id_demande); 
} else {
    echo "Paramètres manquants dans l'URL.";
    exit;
}


$id_contrat = ($cc = $db->getidcontract()) && isset($cc['nextval']) ? $cc['nextval'] : '';



$pdo = $db->getConnection();
$contratManagement = new contratManager($pdo);


$id_demande = $_GET['id_demande'] ?? '';
$annee_demande = $_POST['annee_demande'] ?? '';
$date_demande = date('Y-m-d');


$success = $contratManagement->enregistrerContrat($id_demande, $annee_demande, $date_demande, $id_contrat);


$id_demande = isset($_GET['id_demande']) ? intval($_GET['id_demande']) : 0;

$pieces_jointes = $db->getPiecesJointesByDemande($id_demande);
$agent = $db->getAgent($id_demande);
$deposant = $db->getDeposant($id_demande);
$sujetContrat = $db->getSubject($id_demande);



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  error_log("Données POST : " . print_r($_POST, true));
  $message2 = $db->insertContractData2($pdo);
  echo $message2;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  error_log("Requête POST reçue : " . print_r($_POST, true));
  if (isset($_POST['submit_parties'])) {
      error_log("Données POST pour idPersonnes : " . print_r($_POST, true));
      $idPER = $db->idPersonnes($pdo);
      if ($idPER === "✅") {
          echo "<script>alert('تم حفظ البيانات بنجاح');</script>";
      } else {
          echo "<script>alert('خطأ: " . addslashes($idPER) . "');</script>";
      }
  } else {
      error_log("Erreur : submit_parties non défini dans POST");
      echo "<script>alert('Erreur : Le bouton حفظ n\'a pas été détecté.');</script>";
  }
}

$message3 = $db->insertContractData3($pdo);
echo $message3;

$message4 = $db->insertContractData4($pdo);
echo $message4;

$chapitre = $db->insertChapitres($pdo);
echo $chapitre;

$idPER = $db->idPersonnes($pdo);
echo $idPER;

$revVal = $db->validationRevision($pdo);
echo $revVal ;

$prix = $db->validerPrix($pdo);
echo $prix;

$perc1 = $db->perception1($pdo);
echo $perc1;

$perc4 = $db->perception4($pdo);
echo $perc4;



$titre = $db->nomTITRE($pdo);
echo $titre;

$gouv = $db->nomGOUV($pdo);
echo $gouv;

?> 




<!DOCTYPE html>
<html dir="rtl" lang="ar">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>نظام معالجة العقود</title>
    <link rel="stylesheet" href="css/Traitement.css" />
  </head>

  <body>
    <div class="container">
      <!-- Sidebar Navigation -->
      <div class="sidebar">
        <div id="general-data" class="menu-item active">معطيات عامة</div>
        <div id="documents" class="menu-item">المؤيدات</div>
        <div id="contract-parties" class="menu-item">أطراف التعاقد</div>
        <div id="property-burdens" class="menu-item">التحملات على العقار</div>
        <div id="contract-terms" class="menu-item">الأحكام التعاقدية</div>
        <div id="extraction" class="menu-item">الاستخلاص</div>
      </div>

       <!-- General Data Section -->
      <div id="general-data-content" class="main-content active">
      <form action="save_contract.php" method="POST">
                <div class="top-bar">
                  <div class="search-form">
                    <span>عدد مطلب التحرير</span>
                    <input type="text" class="search-input" name="id_demande" 
                    value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                    <span>/</span>
                    <input type="text" class="search-input" name="annee_demande" 
                    value="<?php echo isset($demande['annee_demande']) ? date('Y', strtotime($demande['annee_demande']))  : ''; ?>" />           
                    <span>تاريخه</span>
                    <input type="text" class="search-input" name="date_demande" 
                    value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                    <span>عدد العقد</span>
                    <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />          
                  </div>
                  <img src="media/logo.png" alt="ONPFF" class="logo" />
                </div>
                <div id="form-container">
                  <div class="subject-field">
                    <span>موضوع العقد</span>
                    <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />
                    </div>
                  <div class="section-title">القسم الأول : البيانات المتعلقة بطالب الخدمة</div>
                  <div class="person-info">
                    <div class="person-title">طالب الخدمة</div>
                      <div class="person-field">
                        <span style="margin: 3px">الاسم</span>
                        <input type="text" name="prenom_deposant" value="<?= $deposant ? htmlspecialchars($deposant['prenom_deposant']) : '' ?>" />
                      </div>
                    <div class="person-field">
                      <span style="margin: 3px">اللقب</span>
                      <input type="text" name="nom_deposant" value="<?= $deposant ? htmlspecialchars($deposant['nom_deposant']) : '' ?>" />
                    </div>
                  </div>
                  <div class="section-title"> القسم الثاني : البيانات المتعلقة بهوية و التزامات المحرر</div>
                  <div class="identity-section">
                  <div class="identity-title">هوية و التزامات المحرر</div>
                  <div class="identity-text">
                    عملا بأحكام الفصل 377 ثالثا من مجلة الحقوق العينية أشهد أنا محرر العقد :
                  </div>
                  <!-- Conteneur global centré -->
                  <div style="display: flex; justify-content: center; margin-top: 30px; direction: rtl;">

                    <!-- Conteneur interne en ligne -->
                    <div style="display: flex; gap: 20px; align-items: center;">

                      <!-- Champ prénom -->
                      <div>
                        <label for="prenom_admin">اسم المحرر</label><br>
                        <input type="text" id="prenom" name="prenom"
                          value="<?php echo isset($_SESSION['userAuth']['prenom_admin']) ? htmlspecialchars($_SESSION['userAuth']['prenom_admin']) : ''; ?>"
                          readonly />
                      </div>

                      <!-- Champ nom -->
                      <div>
                        <label for="nom_admin">لقب المحرر</label><br>
                        <input type="text" id="nom" name="nom"
                          value="<?php echo isset($_SESSION['userAuth']['nom_admin']) ? htmlspecialchars($_SESSION['userAuth']['nom_admin']) : ''; ?>"
                          readonly />
                      </div>

                      <!-- Champ CIN -->
                      <div>
                        <label for="cin_admin">رقم التعريف</label><br>
                        <input type="text" id="cin" name="cin"
                          value="<?php echo isset($_SESSION['userAuth']['cin_admin']) ? htmlspecialchars($_SESSION['userAuth']['cin_admin']) : ''; ?>"
                          readonly />
                      </div>
                    </div>
                  </div>
                  <!-- Section suivante -->
                  <div class="identity-section">
                    <div class="identity-title">
                      إني إطلعت على الرسم (الرسوم) العقاري(ة)
                    </div>
                  </div>
                </div>
                  <div class="final-text"> موضوع هذا الصك و  أشعرت الأطراف بالحالة القانونية الواردة به (بها) و المضمنة صلب هذا العقد و بعدم وجود مانع التحرير<br/></div>
                  </div>
              </form>
            </div>
            <!-- Documents Section -->
            <div id="documents-content" class="main-content">
              <div class="top-bar">
                <div class="search-form">
                <span>عدد مطلب التحرير</span>
                  <input type="text" class="search-input" name="id_demande" 
                  value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                  <span>/</span>
                  <input type="text" class="search-input" name="annee_demande" 
                  value="<?php echo isset($demande['annee_demande']) ? date('Y', strtotime($demande['annee_demande']))  : ''; ?>" />           
                  <span>تاريخه</span>
                  <input type="text" class="search-input" name="date_demande" 
                  value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                  <span>عدد العقد</span>
                  <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />          </div>
                <img src="media/logo.png" alt="ONPFF" class="logo" />
              </div>

              <div class="section-title">القسم الثالث : البيانات المتعلقة بالمؤيدات</div>
              <form method="POST" action="">
              <table id="documents-table">
                  <thead>
                      <tr>
                          <th>ع ر</th>
                          <th>الوثيقة</th>
                          <th>تاريخها</th>
                          <th>مراجع التسجيل</th>
                          <th>تاريخها</th>
                          <th>نوع الوثيقة</th>
                      </tr>
                  </thead>
                  <tbody>
                      <?php if (!empty($pieces_jointes)): ?>
                          <?php $compteur = 1; ?>
                          <?php foreach ($pieces_jointes as $piece): ?>
                              <tr>
                                <td><?php echo $compteur++; ?></td>
                                <td><input type="text" name="libile_pieces[]" value="<?php echo htmlspecialchars($piece['libile_pieces']); ?>" /></td>
                                <td><input type="text" name="date_document[]" value="<?php echo htmlspecialchars($piece['date_document']); ?>" /></td>
                                <td><input type="text" name="ref_document[]" value="<?php echo htmlspecialchars($piece['ref_document']); ?>" /></td>
                                <td><input type="text" name="date_ref[]" value="<?php echo htmlspecialchars($piece['date_ref']); ?>" /></td>
                                <td><input type="text" name="code_pieces[]" value="<?php echo htmlspecialchars($piece['code_pieces']); ?>" /></td>
                                <input type="hidden" name="id_demande[]" value="<?php echo $id_demande; ?>" />
                              </tr>
                          <?php endforeach; ?>
                      <?php else: ?>
                          <tr>
                              <td>1</td>
                              <td><input type="text" name="libile_pieces[]" /></td>
                              <td><input type="text" name="date_document[]" /></td>
                              <td><input type="text" name="ref_inscription[]" /></td>
                              <td><input type="text" name="date_ref[]" /></td>
                              <td><input type="text" name="code_pieces[]" /></td>
                              <input type="hidden" name="id_demande[]" value="<?php echo $id_demande; ?>" />
                          </tr>
                      <?php endif; ?>
                  </tbody>
              </table>
              </form>
            </div>
      <!-- Contract Parties Section -->
      <div id="contract-parties-content" class="main-content">
        <div class="top-bar">
          <div class="search-form">
          <span>عدد مطلب التحرير</span>
            <input type="text" class="search-input" name="id_demande" 
            value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
            <span>/</span>
            <input type="text" class="search-input" name="annee_demande" 
            value="<?php echo isset($demande['annee_demande']) ? date('Y', strtotime($demande['annee_demande']))  : ''; ?>" />           
            <span>تاريخه</span>
            <input type="text" class="search-input" name="date_demande" 
            value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
            <span>عدد العقد</span>
            <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />          </div>
          <img src="media/logo.png" alt="ONPFF" class="logo" />
        </div>

        <div class="table-container">
          <div class="section-title">القسم الرابع : البيانات المتعلقة بأطراف التعاقد</div>
          <form method="POST" action="">
          <table id="parties-table">
            <thead>
              <tr>
                <th>الاسم الثلاثي للمتعاقد</th>
                <th>تسمية الطرف</th>
                <th>الصفة</th>
              </tr>
            </thead>
            <tbody>
              <tr>
              <td><input type="text" name="nom_complet_vendeur" required /></td>
              <td><div class="section1">
                      <button id="openModalBtn" class="btn">إضافة وثيقة الهوية</button>
                      <div id="myModal" class="modal">
                        <div class="modal-content">
                          <span class="close">&times;</span>
                          <h2>وثيقة الهوية</h2>

                          <form id="identityForm">
                            <div class="form-section">
                              <div class="form-group">
                                <label for="numero_document_identite">رقم وثيقة الهوية</label>
                                <input type="text" id="numero_document_identite" name="numero_document_identite" required />
                              </div>
                            </div>

                            <div class="form-section">
                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="prenom">الإسم</label>
                                    <input
                                      type="text"
                                      id="prenom"
                                      name="prenom"
                                      required
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="prenom_pere">إسم الأب</label>
                                    <input
                                      type="text"
                                      id="prenom_pere"
                                      name="prenom_pere"
                                      required
                                    />
                                  </div>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="nom">اللقب</label>
                                    <input
                                      type="text"
                                      id="nom"
                                      name="nom"
                                      required
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="date_emission_document">تاريخ إصدارها</label>
                                    <input
                                      type="date"
                                      id="date_emission_document"
                                      name="date_emission_document"
                                      required
                                    />
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-section">
                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="sexe">الجنس</label>
                                    <select id="sexe" name="sexe" required>
                                      <option value="">اختر الجنس</option>
                                      <option value="male">ذكر</option>
                                      <option value="female">أنثى</option>
                                    </select>
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="nationalite">الجنسبة</label>
                                    <input
                                      type="text"
                                      id="nationalite"
                                      name="nationalite"
                                      required
                                    />
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-section">
                              <div class="form-group">
                                <label for="adresse">العنوان</label>
                                <input type="text" id="adresse" name="adresse" required />
                              </div>
                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="profession">المهنة</label>
                                    <input
                                      type="text"
                                      id="profession"
                                      name="profession"
                                      required
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="etat_civil">الحالة العائلية</label>
                                    <select
                                      id="etat_civil"
                                      name="etat_civil"
                                      required
                                    >
                                      <option value="">اختر الحالة</option>
                                      <option value="single">أعزب</option>
                                      <option value="married">متزوج</option>
                                      <option value="divorced">مطلق</option>
                                      <option value="widowed">أرمل</option>
                                    </select>
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-section">
                              <div class="form-section-title">
                                النظام المالي للزواج حسب الحالة المدنية
                              </div>
                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="prenom_conjoint">إسم الزوج (ة)</label>
                                    <input type="text" id="prenom_conjoint" name="prenom_conjoint" required/>
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="prenom_pere_conjoint">إسم الأب</label>
                                    <input
                                      type="text"
                                      id="prenom_pere_conjoint"
                                      name="prenom_pere_conjoint"
                                      required
                                    />
                                  </div>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="prenom_grand_pere_conjoint">إسم الجد</label>
                                    <input
                                      type="text"
                                      id="prenom_grand_pere_conjoint"
                                      name="prenom_grand_pere_conjoint"
                                      required
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="nom_conjoint">اللقب</label>
                                    <input
                                      type="text"
                                      id="nom_conjoint"
                                      name="nom_conjoint"
                                    />
                                  </div>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="date_naissance_conjoint">تاريخ الولادة</label>
                                    <input
                                      type="date"
                                      id="date_naissance_conjoint"
                                      name="date_naissance_conjoint"
                                      required
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="lieu_naissance_conjoint">مكانها</label>
                                    <input
                                      type="text"
                                      id="lieu_naissance_conjoint"
                                      name="lieu_naissance_conjoint"
                                      required
                                    />
                                  </div>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="nationalite_conjoint">الجنسبة</label>
                                    <input
                                      type="text"
                                      id="nationalite_conjoint"
                                      name="nationalite_conjoint"
                                      required
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="numero_document_conjoint">رقمها</label>
                                    <input
                                      type="text"
                                      id="numero_document_conjoint"
                                      name="numero_document_conjoint"
                                      required
                                    />
                                  </div>
                                </div>
                              </div>

                              <div class="form-row">
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="date_document_conjoint">تاريخها</label>
                                    <input
                                      type="date"
                                      id="date_document_conjoint"
                                      name="date_document_conjoint"
                                    />
                                  </div>
                                </div>
                                <div class="form-col">
                                  <div class="form-group">
                                    <label for="marriageCertificatePlace">مكانها</label>
                                    <input
                                      type="text"
                                      id="lieu_document_conjoint"
                                      name="lieu_document_conjoint"
                                    />
                                  </div>
                                </div>
                              </div>
                            </div>

                            <div class="form-section">
                              <div class="form-group">
                                <label for="notes">ملاحظات</label>
                                <textarea
                                  id="notes"
                                  name="notes"
                                  class="notes-area"
                                ></textarea>
                              </div>
                            </div> 
                            <div id="overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:999;" onclick="hideMessage()"></div>
                          
                          </form>
                        </div>  
                      </div> 
                    </div> 
                  </td>
                  <td>
                  <select name="vendeur_acheteur" id="vendeur_acheteur" required>
                        <option value="vendeur_acheteur">صفة المتعاقد ..</div></option>
                        <option value="vendeur">البائع</option>
                        <option value="acheteur">المشتري</option>
                      </select>               
                  </td>
                </tr>
            </tbody>
          </table>
          </form>
          <div class="form-actions">
          <button type="submit" name="submit_parties" class="btn-save">حفظ</button>            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
          </div>
        </div>
      </div>
    
    
      <!-- Property Burdens Section -->
      <div id="property-burdens-content" class="main-content">
            <div class="top-bar">
              <div class="search-form">
                <span>عدد مطلب التحرير</span>
                <input type="text" class="search-input" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
                <span>/</span>
                <input type="text" class="search-input" name="annee_demande" value="<?php echo isset($demande['annee_demande']) ? date('Y', strtotime($demande['annee_demande']))  : ''; ?>" />           
                <span>تاريخه</span>
                <input type="text" class="search-input" name="date_demande" value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
                <span>عدد العقد</span>
                <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />
              </div>
              <img src="media/logo.png" alt="ONPFF" class="logo" />
            </div>

            <form method="POST" action="">
              <div class="section-title"> القسم الخامس : البيانات المتعلقة بموضوع التعاقد و مراجع انجراره بالرسم العقاري </div>
              <table>
                  <thead>
                      <tr>
                          <th>عدد الحق</th>
                          <th>موضوع التعاقد</th>
                          <th>الوحدة</th>
                          <th>التجزئة العامة</th>
                          <th>المحتوى</th>
                          <th>الثمن</th>
                          <th>المدة</th>
                          <th>الفائض</th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td><input type="text" name="nom_droit1" required /></td>
                          <td><input type="text" name="sujet_contrat1" required /></td>
                          <td><input type="text" name="unite1" required /></td>
                          <td><input type="number" name="detail_general" required /></td>
                          <td><input type="text" name="contenu1" required /></td>
                          <td><input type="text" name="valeur_prix1" required /></td>
                          <td><input type="text" name="dure1" required /></td>
                          <td><input type="text" name="surplus1" required /></td>
                      </tr>
                  </tbody>
              </table>
              <div class="form-actions">
                <button type="submit" name="submit" class="btn-save">حفظ</button>
                <button type="button" class="btn-delete">حذف</button>
                <button type="button" class="btn-add">إضافة سطر</button>
              </div>
            </form>
            

            <form method="POST" action="">
              <h3>بيانات تتعلق بمراجع انجرار الترسيم</h3>
                <table id="inscription-table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الايداع</th>
                            <th>المجلد</th>
                            <th>العدد</th>
                            <th>ع.الفرعي</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="date" name="date_inscri2[]" value="" required></td>
                            <td><input type="text" name="lieu_inscri2[]" value="" required></td>
                            <td><input type="text" name="doc2[]" value="" required></td>
                            <td><input type="text" name="num_inscri2[]" value="" required></td>
                            <td><input type="text" name="num_succursale2[]" value="" required></td>
                        </tr>
                    </tbody>
                </table>
                <div class="form-actions">
                    <button type="submit" name="submit" class="btn-save">حفظ</button>
                    <button type="button" class="btn-delete">حذف</button>
                    <button type="button" class="btn-add">إضافة سطر</button>
                </div>
            </form>


             





              <form method="POST" action="">
                <h3>البيانات الأخرى المتعلقة بالحق</h3>
                <table>
                  <thead>
                    <tr>
                      <th> النظام المالي للزواج</th>
                      <th> ملاحظات أخرى</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                    <td><input type="text" name="regime_finance_couple3" required /></td>
                    <td><input type="text" name="remarques3" required /></td>
                    
                    </tr>
                  </tbody>
                </table>
                </form>
                <div class="form-actions">
                  <button type="submit" name="submit" class="btn-save">حفظ</button>
                  <button type="button" class="btn-delete">حذف</button>
                  <button type="button" class="btn-add">إضافة سطر</button>
                </div>          
              </form> 




              <form method="POST" action="">
                <h3>المبلغ الجملي لموضوع التعاقد</h3>
                <table>
                  <thead>
                    <tr>
                      <th> قيمة موضوع التعاقد بالدينار</th>
                      <th>  المبلغ بلسان القلم</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" name="valeur_contrat_dinar" required /></td>
                      <td><input type="text" name="prix_ecriture" required /></td>
                    </tr>
                  </tbody>
                </table>
                <div class="form-actions">
                  <button type="submit" name="submit" class="btn-save">حفظ</button>
                  <button type="button" class="btn-delete">حذف</button>
                  <button type="button" class="btn-add">إضافة سطر</button>
                </div>
              </form>

              <form method="POST" action="">
                <h3>تفاصيل العقار </h3>
                <table>
                  <thead>
                    <tr>
                      <th> اسم الولاية </th>
                      <th>  اسم العقار بالكامل  </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><input type="text" name="libile_gouv" required /></td>
                      <td><input type="text" name="gouv_titre" required /></td>
                    </tr>
                  </tbody>
                </table>
                <div class="form-actions">
                  <button type="submit" name="submit" class="btn-save">حفظ</button>
                  <button type="button" class="btn-delete">حذف</button>
                  <button type="button" class="btn-add">إضافة سطر</button>
                </div>
              </form>


              
            </form>          
        </div>
      </div>

      <!-- Contract Terms Section -->
      <div id="contract-terms-content" class="main-content">
        <div class="top-bar">
          <div class="search-form">
            <span>عدد مطلب التحرير</span>
            <input type="text" class="search-input" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
            <span>/</span>
            <input type="text" class="search-input" name="annee_demande" value="<?php echo isset($demande['annee_demande']) ? date('Y', strtotime($demande['annee_demande']))  : ''; ?>" />           
            <span>تاريخه</span>
            <input type="text" class="search-input" name="date_demande" value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
            <span>عدد العقد</span>
            <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />
          </div>
          <img src="media/logo.png" alt="ONPFF" class="logo" />
        </div>

        <h2 class="section-title"> القسم السادس : البيانات المتعلقة بالأحكام التعاقدية</h2>
        <form method="POST" action="">
          <div>المحتوى</div>
          <textarea
            name="contenue_chapitre"
            style="width: 80%; height: 50%; border-radius: 10px"
            required
          ></textarea>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
          </div>
        </form>
      </div>

      <!-- Extraction Section -->
      <div id="extraction-content" class="main-content">
        <div class="top-bar">
          <div class="search-form">
            <span>عدد مطلب التحرير</span>
            <input type="text" class="search-input" name="id_demande" value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />
            <span>/</span>
            <input type="text" class="search-input" name="annee_demande" value="<?php echo isset($demande['annee_demande']) ? date('Y', strtotime($demande['annee_demande']))  : ''; ?>" />           
            <span>تاريخه</span>
            <input type="text" class="search-input" name="date_demande" value="<?php echo isset($demande['date_demande']) ? htmlspecialchars($demande['date_demande']) : ''; ?>" />
            <span>عدد العقد</span>
            <input type="text" class="search-input" value="<?php echo htmlspecialchars($id_contrat); ?>" />
          </div>
          <img src="media/logo.png" alt="ONPFF" class="logo" />
        </div>

        <div class="section-title">القسم السابع : امضاءات الأطراف و التعريف بها</div>
        
        <!-- Signatures Form -->
        <form method="POST" action="" class="extraction-form">
          <table class="documents-table">
            <thead>
              <tr>
                <th>الاسم</th>
                <th>اسم الأب</th>
                <th>اسم الجد</th>
                <th>اللقب</th>
                <th>الصفة</th>
                <th>الامضاءات</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="prenom_personne" required/></td>
                <td><input type="text" name="prenom_pere" required/></td>
                <td><input type="text" name="prenom_grandpere" required/></td>
                <td><input type="text" name="nom_personne" required/></td>
                <td><input type="text" name="statut" required/></td>
                <td><input type="text" name="signature" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Fees Form -->
        <form method="POST" action="" class="extraction-form">
          <h3>معاليم التحرير و مراجع الاستخلاص</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th>معرف المعلوم</th>
                <th> الجهة المستخلصة</th>
                <th> المبلغ المستوجب</th>
                <th>المبلغ المستخلص</th>
                <th>عدد الوصل</th>
                <th>التاريخ</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="id_montant1" required/></td>
                <td><input type="text" name="partieabstrait1" required/></td>
                <td><input type="text" name="montant_obligatoire1" required/></td>
                <td><input type="text" name="montant_paye1" required/></td>
                <td><input type="text" name="num_recu1" required/></td>
                <td><input type="text" name="date_payement1" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Total Form -->
        <form method="POST" action="" class="extraction-form">
          <h3>المجموع</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th> مجموع المبلغ المستوجب</th>
                <th> مجموع المبلغ المستخلص </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="somme_prix_obligatoire" required/></td>
                <td><input type="text" name="somme_prix_paye" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Contract Confirmation Form -->
        <form method="POST" action="" class="extraction-form">
          <h3>البيانات المتعلقة بتأكيد العقد</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th>الصفة</th>
                <th> التلقي</th>
                <th>التحرير</th>
                <th> المراجعة</th>
                <th>المصادقة النهائية</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="statut2" required/></td>
                <td><input type="text" name="redacteur2" required/></td>
                <td><input type="text" name="redaction2" required/></td>
                <td><input type="text" name="revision2" required/></td>
                <td><input type="text" name="validationFinal2" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Registration Form -->
        <form method="POST" action="" class="extraction-form">
          <h3>البيانات المتعلقة بتسجيل العقد لدى القباضة المالية و استخلاص معلوم ادارة الملكية العقارية</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th> القيمة بالدينار</th>
                <th>  النسبة</th>
                <th>  المبلغ بالدينار</th>
                <th>ختم قابض التسجيل و امضاؤه</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="valeur_dinar3" required/></td>
                <td><input type="text" name="pourcent3" required/></td>
                <td><input type="text" name="montant_dinar3" required/></td>
                <td><input type="text" /></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>

        <!-- Property Services Form -->
        <form method="POST" action="" class="extraction-form">
          <h3>البيانات المتعلقة بتصفية معاليم الخدمات الراجعة لادارة الملكية العقارية</h3>
          <table class="documents-table">
            <thead>
              <tr>
                <th>  التسمية</th>
                <th>  القيمة بالدينار</th>
                <th> النسبة</th>
                <th> المبلغ بالدينار</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" name="nom4" required/></td>
                <td><input type="text" name="valeur_dinar4" required/></td>
                <td><input type="text" name="pourcent4" required/></td>
                <td><input type="text" name="montant_dinar4" required/></td>
              </tr>
            </tbody>
          </table>
          <div class="form-actions">
            <button type="submit" name="submit" class="btn-save">حفظ</button>
            <button type="button" class="btn-delete">حذف</button>
            <button type="button" class="btn-add">إضافة سطر</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="script/script.js"></script>
  </body>
</html>