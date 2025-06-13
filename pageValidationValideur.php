<?php

require_once 'connect.php';
$connect = new ClsConnect();
$pj = $connect->getPiecesJointesV(); 
$ID1 = $connect->getDI1();
$ID2 = $connect->getDI2();
$ID3 = $connect->getDI3();
$ID4 = $connect->getDI4();
$chj = $connect->getChJ();
$PC = $connect->getPerContracV();
$Per1 = $connect->getPer1();
$Per2 = $connect->getPer2();
$Per3 = $connect->getPer3();
$Per4 = $connect->getPer4();
$som = $connect->getSomme();
$IDpersonne = $connect->getIDpersonne();
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface SQL - إدارة البيانات العقارية</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, #a8d8ea 0%, #88c3d8 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 20px;
            direction: rtl;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.2);
            padding: 20px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            color: #2d4a5a;
            font-size: 2.5em;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .header p {
            color: #4a6c7c;
            font-size: 1.2em;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: #2d4a5a;
            font-size: 1.2em;
        }

        .spinner {
            border: 4px solid #a8d8ea;
            border-top: 4px solid #2d4a5a;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error {
            background: rgba(248, 215, 218, 0.9);
            color: #721c24;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            border-left: 4px solid #dc3545;
        }

        .tables-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 25px;
            margin-bottom: 30px;
        }

        .table-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .table-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .table-title {
            background: linear-gradient(45deg, #5cb8e6, #4ca8dc);
            color: white;
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 1.3em;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 15px rgba(92, 184, 230, 0.3);
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            direction: rtl;
        }

        .data-table th {
            background: linear-gradient(45deg, #66bbe6, #4ca8dc);
            color: white;
            padding: 15px 12px;
            text-align: right;
            font-weight: 600;
            font-size: 1.1em;
            border-bottom: 2px solid #4ca8dc;
        }

        .data-table td {
            padding: 12px;
            border-bottom: 1px solid #e8f4f9;
            transition: background-color 0.2s ease;
            text-align: right;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8fcfe;
        }

        .data-table tr:hover {
            background-color: #e8f4f9;
            transform: scale(1.01);
        }

        .data-table td:first-child {
            font-weight: 600;
            color: #2d4a5a;
        }

        .number-highlight {
            background: linear-gradient(45deg, #81c8e6, #66bbe6);
            color: white;
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: bold;
            display: inline-block;
        }

        .vertical-layout {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .refresh-btn, .action-btn {
            background: linear-gradient(45deg, #5cb8e6, #4ca8dc);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 10px;
            box-shadow: 0 4px 15px rgba(92, 184, 230, 0.3);
        }

        .refresh-btn:hover, .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(92, 184, 230, 0.4);
        }

        .action-buttons {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
        }

        .print-btn {
            background: linear-gradient(45deg, #28a745, #20923b);
        }

        .object-btn {
            background: linear-gradient(45deg, #dc3545, #c82333);
        }

        .print-btn:hover {
            box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
        }

        .object-btn:hover {
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.4);
        }
        

        @media (max-width: 768px) {
            .tables-grid {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏢 واجهة تأكيد البيانات العقارية</h1>
            <span class="header">عدد مطلب التحرير</span>
            <input type="text" class="refresh-btn" name="id_demande" 
            value="<?php echo isset($demande['id_demande']) ? htmlspecialchars($demande['id_demande']) : ''; ?>" />            
        </div>

        <div class="vertical-layout">
            <div class="tables-grid">           
                
                <!-- البيانات المتعلقة بالمؤيدات --> 
                <div class="table-section">
                    <div class="table-title">📋 البيانات المتعلقة بالمؤيدات</div>
                    <?php if (!empty($pj)): ?>
                        <table class="data-table">
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
                                <?php $compteur = 1; ?>
                                <?php foreach ($pj as $ligne): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($ligne['libile_pieces'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($ligne['date_document'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($ligne['ref_document'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($ligne['date_ref'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($ligne['code_pieces'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- personnes contracteurs -->
            <div class="table-section">
                <div class="table-title"> 📋البيانات المتعلقة بأطراف التعاقد</div>
                <?php if (!empty($PC)): ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>الاسم الثلاثي بالكامل</th>
                                <th>الصفة</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $compteur = 1; ?>
                            <?php foreach ($PC as $pc): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($compteur++); ?></td>
                                <td><?php echo htmlspecialchars($pc['nom_complet_personne'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($pc['role'] ?? ''); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                <?php endif; ?>
            </div>
        
            
                <!-- البيانات المتعلقة بموضوع التعاقد -->
                <div class="table-section">
                    <div class="table-title">🏠 البيانات المتعلقة بموضوع التعاقد ومراجع انجراره بالرسم العقاري</div>
                    <?php if (!empty($ID1)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
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
                                <?php $compteur = 1; ?>
                                <?php foreach ($ID1 as $contrat): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($contrat['nom_droit1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['sujet_contrat1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['unite1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['detail_general'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['contenu1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['valeur_prix1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['dure1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($contrat['surplus1'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>
        
                <!-- بيانات تتعلق بمراجع انجرار الترسيم -->
                <div class="table-section">
                    <div class="table-title">📝 بيانات تتعلق بمراجع انجرار الترسيم</div>
                    <?php if (!empty($ID2)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>تاريخ التسجيل</th>
                                    <th>مكان الإيداع</th>
                                    <th>رقم المجلد</th>
                                    <th>عدد التسجيل</th>
                                    <th>ع الفرعي</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($ID2 as $immobilier): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['date_inscri2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['lieu_inscri2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['doc2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['num_inscri2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['num_succursale2'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>

                <!--  البيانات الأخرى المتعلقة بالحق -->
                <div class="table-section">
                    <div class="table-title">📝 البيانات الأخرى المتعلقة بالحق</div>
                    <?php if (!empty($ID3)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>النظام المالي للزواج</th>
                                    <th>ملاحظات أخرى</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($ID3 as $immobilier): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['regime_finance_couple3'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['remarques3'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>

                <!--  المبلغ الجملي لموضوع التعاقد -->
                <div class="table-section">
                    <div class="table-title">📝 المبلغ الجملي لموضوع التعاقد</div>
                    <?php if (!empty($ID4)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المبلغ بلسان القلم</th>
                                    <th>قيمة موضوع التعاقد بالدينار</th>
                                    <th>عدد المطلب</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($ID4 as $immobilier): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['prix_ecriture'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['valeur_contrat_dinar'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($immobilier['id_demande'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>



                <!-- chapitres juridiques -->
                <div class="table-section">
                    <div class="table-title">📝 الأحكام التعاقدية الأخرى</div>
                    <?php if (!empty($chj)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>المحتوى</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($chj as $chapitre): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($chapitre['contenue_chapitre'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>

                <!-- IDPersonnes -->
                <div class="table-section">
                    <div class="table-title">📝 امضاءات الأطراف و التعريف بها</div>
                    <?php if (!empty($IDpersonne)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الاسم</th>
                                    <th>اسم الأب</th>
                                    <th>اسم الجد</th>
                                    <th>اللقب</th>
                                    <th>الصفة</th>
                                    <th>الامضاءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($IDpersonne as $personne): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($personne['prenom_personne'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($personne['prenom_pere'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($personne['prenom_grandpere'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($personne['nom_personne'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($personne['statut'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($personne['signature'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>


                <!-- perception1 -->
                <div class="table-section">
                    <div class="table-title">📝 معاليم التحرير و مراجع الاستخلاص</div>
                    <?php if (!empty($Per1)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>معرف المعلوم</th>
                                    <th>الجهة المستخلصة</th>
                                    <th>المبلغ المستوجب</th>
                                    <th>المبلغ المستخلص</th>
                                    <th>عدد الوصل</th>
                                    <th>التاريخ</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($Per1 as $perception): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($perception['id_montant1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['partieabstrait1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['montant_obligatoire1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['montant_paye1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['num_recu1'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['date_payement1'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>

                 <!-- sommme -->
                 <div class="table-section">
                    <div class="table-title">📝 المجموع</div>
                    <?php if (!empty($som)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>مجموع المبلغ المستوجب</th>
                                    <th>مجموع المبلغ المستخلص</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($som as $somme): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($somme['somme_prix_obligatoire'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($somme['somme_prix_paye'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>
                <!-- perception3 -->
                <div class="table-section">
                    <div class="table-title">📝 البيانات المتعلقة بتسجيل العقد لدى القباضة المالية و استخلاص معلوم ادارة الملكية العقارية</div>
                    <?php if (!empty($Per3)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>القيمة بالدينار</th>
                                    <th>النسبة</th>
                                    <th>المبلغ بالدينار</th>
                                    <th>ختم قابض التسجيل و امضاؤه</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($Per3 as $perception): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($perception['valeur_dinar3'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['pourcent3'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['montant_dinar3'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($perception['signature3'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>

                <!-- perception2 -->
                <div class="table-section">
                    <div class="table-title">📝البيانات المتعلقة بتأكيد العقد</div>
                    <?php if (!empty($Per2)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>الصفة</th>
                                    <th>التلقي</th>
                                    <th>التحرير</th>
                                    <th>المراجعة</th>
                                    <th>المصادقة النهائية</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($Per2 as $p2): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($compteur++); ?></td>
                                    <td><?php echo htmlspecialchars($p2['statut2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p2['redacteur2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p2['redaction2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p2['revision2'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p2['validation_final2'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>
                <!-- perception4 -->
                <div class="table-section">
                    <div class="table-title">📝البيانات المتعلقة بتصفية معاليم الخدمات الراجعة لادارة الملكية العقارية</div>
                    <?php if (!empty($Per4)): ?>
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>التسمية</th>
                                    <th>القيمة بالدينار</th>
                                    <th>النسبة</th>
                                    <th>الميلغ بالدينار</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $compteur = 1; ?>
                                <?php foreach ($Per4 as $p4): ?>
                                <tr>
                                    <td><?php echo $compteur++; ?></td>
                                    <td><?php echo htmlspecialchars($p4['nom4'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p4['valeur_dinar4'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p4['pourcent4'] ?? ''); ?></td>
                                    <td><?php echo htmlspecialchars($p4['montant_dinar4'] ?? ''); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p style="text-align: center; color: #666; padding: 20px;">لا توجد بيانات متاحة</p>
                    <?php endif; ?>
                </div>

                

              

            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="action-buttons">
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="print_contract">
                <button type="submit" class="action-btn print-btn">🖨️ طباعة العقد</button>
            </form>
            
            <form method="POST" style="display: inline;">
                <input type="hidden" name="action" value="object_contract">
                <button type="submit" class="action-btn object-btn">⚠️ اعتراض على العقد</button>
            </form>
        </div>
    </div>
</body>
</html>