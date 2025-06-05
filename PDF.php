<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;
$dompdf = new Dompdf();
ob_start();
?>



<!DOCTYPE html>
<html dir="rtl" lang="ar">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>وعد بيع</title>
    <link rel="stylesheet" href="css/PDF.css">
  </head>
  <body>
    <div class="document">
      <div class="header">
        <div style="text-align: right">تاريخ التحرير : *******</div>
        <div style="text-align: center">
          <strong>الجمهورية التونسية</strong><br />
          وزارة أملاك الدولة والشؤون العقارية<br />
          الديوان الوطني للملكية العقارية<br />
          الإدارة الجهوية للملكية العقارية ب*****
        </div>
      </div>

      <div
        style="
          display: flex;
          justify-content: space-between;
          align-items: center;
        "
      >


      </div>

      <div
        style="display: flex; justify-content: space-between; margin: 10px 0"
      >
        <div>
          <span>عدد مطلب التحرير : </span>
          <span>*************</span>
          <span>************</span>
        </div>
        <div>
          <span>عدد الوصل : ************</span>
          <span>   ******** :تاريخه</span>
        </div>
        <div>
          <span>عدد العقد : </span>
          <span>************</span>
        </div>
      </div>



      <div
        class="title-box"
        style="
          display: block;
          text-align: center;
          width: 150px;
          margin: 10px auto;
        "
      >
      </div>
      <div style=" margin: 5px 0">موضوع العقد</div>

      <div class="section">
        <div class="section-title">
          القسم الأول: البيانات المتعلقة بطالب الخدمة
        </div>
        <table>
          <tr>
            <td>الإسم :</td>
            <td>******</td>
          </tr>
          <tr>
            <td>اللقب :</td>
            <td>*****</td>
          </tr>
        </table>
      </div>

      <div class="section">
        <div class="section-title">
          القسم الثاني : البيانات المتعلقة بهوية والتزامات المحرر
        </div>
        <table>
          <tr>
            <td>الإسم :</td>
            <td>*****</td>
          </tr>
          <tr>
            <td>اللقب :</td>
            <td>******</td>
          </tr>
          <tr>
            <td>المهنة :</td>
            <td>*******</td>
          </tr>
          <tr>
            <td>عدد بطاقة تعريف الوطنية :</td>
            <td>*******</td>
          </tr>
        </table>
        <p>أني اطلعت على الرسم العقاري أو الرسوم العقارية :</p>
        <p>
          موضوع هذا الصك وتحررت الأطراف المتعاقدة بالصفة القانونية اللازمة بها
          ومن وجود أو عدم وجود ما يمنع قانوني للتحرير
        </p>
      </div>

      <div class="section">
        <div class="section-title">
          القسم الثالث : البيانات المتعلقة بالمؤيدات
        </div>
        <table>
          <thead>
            <tr>
              <th>ع ر</th>
              <th>الوثيقة</th>
              <th>تاريخها</th>
              <th>مراجع التسجيل </th>
              <th>تاريخها</th>
              <th> نوع الوثيقة</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="section">
        <div class="section-title">
          القسم الرابع : البيانات المتعلقة بأطراف العقد
        </div>
        <div
          style="
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
          "
        >

    </div>
    </div>
    </div>
    <div class="document">
        
        <!-- Premier contrat -->
        <div class="contract-box">
            <div class="contract-header"> 1 المتعاقد
    
            </div>
            <div class="contract-content">
                
                <div class="info-group">
                    <div class="info-right">
                        <div class="info-line">
                            <span class="label">الإسم :</span>
                            <span> ******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">اسم الجد :</span>
                            <span>******</span>
                        </div>
                        <div class="info-line">
                            <span class="label"> تاريخ الولادة :</span>
                            <span>*******</span>
                        </div>
                    </div>
                    <div class="info-left">
                        <div class="info-line">
                            <span class="label">إسم الأب : </span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label"> اللقب:</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">مكانها :</span>
                            <span>*******</span>
                        </div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-right">
                        <div class="info-line">
                            <span class="label">الجنس :</span>
                            <span>*******</span>
                        </div>
                        
                        <div class="info-line">
                            <span class="label">وثيقة الهوية </span>
                            <span>*******</span>
                        </div>


                        <div class="info-line">
                            <span class="label">تاريخ إصدارها :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">المقر :</span>
                            <span>*******</span>
                        </div>
                    </div>
                    <div class="info-left">
                        <div class="info-line">
                            <span class="label">الجنسية :</span>
                            <span>*******</span>
                        </div>

                        <div class="info-line">
                            <span class="label">رقمها :</span>
                            <span>*******</span>
                        </div>

                        <div class="info-line">
                            <span class="label">مكان إصدارها : </span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">الحالة العائلية  : </span>
                            <span>*******</span>
                        </div>

                    </div>
                </div>

                <div class="info-line">
                    <span class="label">المهنة : </span>
                    <span>*******</span>
                </div>

                <div class="signature-box">
                    <div class="signature-text">
                        <div class="bold">النظام المالي للزواج حسب الحالة المدنية :</div>
                        <div>************</div>
                    </div>
                </div>

                <div class="marriage-info">
                    <div class="info-group">
                        <div class="info-right">
                            <div class="info-line">
                                <span class="label">اسم القرين :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">اللقب :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">تاريخ الولادة :</span>
                                <span>*******</span>
                            </div>


                        </div>
                        <div class="info-left">
                            <div class="info-line">
                                <span class="label">إسم الأب :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">إسم الجد :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">مكانها :</span>
                                <span>*******</span>
                            </div>


                        </div>
                    </div>
                    
                    <div class="info-group">
                        <div class="info-right">
                            <div class="info-line">
                                <span class="label">جنسية(ها) :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                
                                <span class="label">وثيقة الهوية : </span>
                                <span>بطاقة تعريف وطنية</span>
                            </div>


                            <div class="info-line">
                                <span class="label">تاريخ إصدارها :</span>
                                <span>*******</span>
                            </div>
                        </div>
                        <div class="info-left">
                            <div class="info-line">
                                <span class="label">رقمها :</span>
                                <span>*******</span>
                            </div>

                            <div class="info-line">
                                <span class="label">مكان إصدارها :</span>
                                <span>*******</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deuxième contrat -->
        <div class="contract-box">
        <div class="contract-header"> 2 المتعاقد
        </div>
            <div class="contract-content">
                
                <div class="info-group">
                    <div class="info-right">
                        <div class="info-line">
                            <span class="label">الإسم :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">مكانها :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">رقمها :</span>
                            <span>*******</span>
                        </div>
                    </div>
                    <div class="info-left">
                        <div class="info-line">
                            <span class="label">إسم الأب :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">تاريخ الولادة :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">وثيقة الهوية : بطاقة تعريف وطنية</span>
                        </div>
                    </div>
                </div>

                <div class="info-group">
                    <div class="info-right">
                        <div class="info-line">
                            <span class="label">الصفة :  </span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">اللقب :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">الجنس :</span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">تاريخ إصدارها :</span>
                            <span>*******</span>
                        </div>
                    </div>
                    <div class="info-left">
                        <div class="info-line">
                            <span class="label">شخص طبيعي</span>
                            <span class="label">إسم الجد : </span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">الجنسية : تونسية</span>
                            <span class="label">العدد :   </span>
                            <span>*******</span>
                        </div>
                        <div class="info-line">
                            <span class="label">مكان إصدارها : </span>
                            <span>*******</span>
                            <span class="label">الحالة المدنية :</span>
                            <span>*******</span>
                        </div>
                    </div>
                </div>

                <div class="info-line">
                    <span class="label">المهنة :  </span>
                    <span>*******</span>
                </div>

                <div class="signature-box">
                    <div class="signature-text">
                        <div>النظام المالي للزواج حسب الحالة المدنية :</div>
                        <span>*******</span>
                    </div>
                </div>

                <div class="marriage-info">
                    <div class="info-group">
                        <div class="info-right">
                            <div class="info-line">
                                <span class="label">اسم القرين </span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">إسم الأب :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">إسم الجد :</span>
                                <span>*******</span>
                            </div>
                            <div class="info-line">
                                <span class="label">اللقب :</span>
                                <span>*******</span> 
                            </div>
                        </div>
                    </div>
                </div>

                <div class="employee-section">
                    <div style="font-size: 10px; text-align: justify;">
                    <span>*******</span>                    </div>
                </div>
            </div>
        </div>
        <div class="section-title">القسم الخامس : البيانات المتعلقة بموضوع التعاقد ومراجع انجراره بالرسم العقاري</div>
        

        <table class="data-table">

            <tr>
                <th>عدد الحق</th>
                <th>موضوع التعاقد</th>
                <th>الوحدة</th>
                <th>التجزئة العامة </th>
                <th>المحتوى</th>
                <th>القيمة او الثمين</th>
                <th>المدة</th>
                <th>الفائض</th>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <div class="header">            بيانات تتعلق انجرار الترسيم 
            <table class="data-table">
                <tr>
                    <th>التاريخ</th>
                    <th>الإيداع</th>
                    <th> المجلد</th>
                    <th>العدد</th>
                    <th>ع.الفرعي</th>

                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
        
            <div class="header">  البيانات الاخرى المتعلقة بالحق

                <table class="data-table">
                    <th>النظام المالي للزواج</th>
                    <th>ملاحظات اخرى</th>
    
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>

                    </tr>
                </table>
            </div>


        <div class="header">المبلغ الجملي لموضوع التعاقد

            <div class="amount-section">
                <div class="amount-row">
                    <span>قيمة موضوع التعاقد بالدينار</span>
                    <span>المبلغ بلسان القلم</span>
                </div>
                <div class="amount-row">
                <span>*******</span>
                    <span>*******</span>
                </div>
            </div>
        </div>
        
    </div>











    <div class="document">
        <div class="document-border">
        <div class="section-title">القسم السادس : الأحكام التعاقدية الأخرى </div>
        <div>
            <span>*******</span>
        </div>

        


        <div class="table-section">
            <div class="section-title">القسم السابع : إمضاءات الأطراف والتعريف بها</div>
            
            <table>
                <thead>
                    <tr>
                        <th>الإسم</th>
                        <th>إسم الأب</th>
                        <th>إسم الجد</th>
                        <th>اللقب</th>
                        <th>الصفة</th>
                        <th>الإمضاءات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td> </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td> </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td> </td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>






    <div class="document">
        <!-- Header Section -->
        <div class="header">
            <h1>البيانات المتعلقة بالسلطة الإدارية المعرفية بإيضاحات الأطراف</h1>
            
            <div class="header-info">
            <span>أنا </span>
            <span>*******</span>
            <span>الصفة : محرر للعقود</span>
            </div>
            
            <div class="header-text">
                بالإدارة الجهوية للملكية العقارية بـ ******
            </div>
            
            <div class="header-text">
                أشهد أن الأطراف الواردة هويتهم أعضاء أمامنا وضمن ذلك صلب هذا العقد تحت عدد : ******
            </div>
            
            <div style="text-align: right; margin-top: 10px;">
                الإمضاء
            </div>
        </div>

        <!-- Main Table Section -->
        <div class="header">معاليم التحرير
        
            <table>
                <thead>
                    <tr>
                        <th>معرف المعلوم</th>
                        <th>الجهة المستخلصة</th>
                        <th>المبلغ المستوجب</th>
                        <th>المبلغ المستخلص</th>
                        <th>عدد الوصل</th>
                        <th>التاريخ</th>
                    
                    </tr>
                </thead>
                <tbody>
                    <tr class="row-11">
                        <td></td>
                        <td> </td>
                        <td class="amount"></td>
                        <td class="amount"></td>
                        <td></td>
                        <td></td>
                        
                    </tr>
                    <tr class="row-12">
                        <td></td>
                        <td> </td>
                        <td class="amount"></td>
                        <td class="amount"></td>
                        <td></td>
                        <td></td>
                        
                    </tr>
                    <tr class="row-14">
                        <td></td>
                        <td> </td>
                        <td class="amount"></td>
                        <td class="amount"></td>
                        <td></td>
                        <td></td>
                        
                    </tr>
                </tbody>
            </table>

            <h3 class="header">المجموع</h3>
              <table class="documents-table">
                <thead>
                  <tr>
                    <th> مجموع المبلغ المستوجب</th>
                    <th> مجموع المبلغ المستخلص </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><input type="text"/></td>
                    <td><input type="text"/></td>
                  </tr>
                </tbody>
              </table>

              <h3 class="header">البيانات المتعلقة بتأكيد العقد</h3>
            <!-- Signatures Table -->
            <table class="signatures-table">
                <thead>
                    <tr>
                        <th>الصفة</th>
                        <th>التلقي</th>
                        <th>التحرير</th>
                        <th>المراجعة</th>
                        <th>المصادقة النهائية</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>الإمضاء</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>الختم</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>التاريخ</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>













        <!-- Final Section -->
        <div class="final-section">
            <div class="header">
                البيانات المتعلقة بتسجيل العقد لدى القباضة المالية واستخلاص معلوم إدارة الملكية العقارية
            </div>
            
            <table>
                <tr>
                    <td class="header" colspan="3">معلوم إدارة الملكية العقارية</td>
                    <td class="header">مراجع التسجيل</td>
                </tr>
                <tr>
                    <td class="header">المبلغ بالدينار</td>
                    <td class="header">النسبة</td>
                    <td class="header">القيمة بالدينار</td>
                    <td rowspan="3"></td>
                </tr>
                <tr>
                    <td class="zero"><span>*************</span></td>
                    <td class="percentage"><span>*************</span></td>
                    <td class="amount"><span>*************</span></td>
                </tr>
                <tr>
                    <td class="footer-text">ختم قابض التسجيل والطابع</td>
                    <td colspan="2" class="footer-text">ختم قابض التسجيل والطابع</td>
                </tr>
            </table>
        </div>
    </div>
    <div class="document-container">
        <div class="document">
            <!-- Title Section -->
            <div class="header">
                البيانات المتعلقة بقضية معاليم الخدمات الراجعة لإدارة الملكية العقارية
            </div>
            
            <!-- Main Table -->
            <table>
                <thead>
                    <tr>
                        <th>التسمية</th>
                        <th>القيمة بالدينار</th>
                        <th>النسبة</th>
                        <th>المبلغ بالدينار</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    
                    </tr>
                    <tr>
                    <td></td>                        
                    <td></td>
                    <td></td>
                    <td></td>
                        
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <!-- Notes Section -->
            <div class="notes-section">
                <div class="note-title">تنبيه</div>
                
                <div class="note-item">
                    * على الطرفين المتعاقدين كل في ما يخصه احترام الإجراءات اللازمة لإتمام هذا العقد بالوجه العقاري
                </div>
                
                <div class="note-item">
                    * إدارة الملكية العقارية لا تتحمل أية مسؤولية عن عدم تسجيل هذا الكتب لدى القباضة المالية أو التأخير في إنجام ذلك من قبل المحرول عليه ذلك
                </div>
            </div>
        </div>

        
    </div>
  </body>
</html>