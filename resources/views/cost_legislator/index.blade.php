<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج تكلفة مشروع للنظام</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: white;
            color: black;
            line-height: 1.2;
            padding: 10px;
            font-size: 12px;
        }
        
        .excel-container {
            width: 100%;
            overflow-x: auto;
        }
        
        .excel-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1300px;
        }
        
        .excel-table th, 
        .excel-table td {
            border: 1px solid #a0a0a0;
            padding: 3px 5px;
            text-align: right;
            vertical-align: middle;
            font-weight: normal;
            height: 25px;
        }
        
        .excel-table th {
            background-color: #d9e1f2;
            font-weight: bold;
            text-align: center;
        }
        
        .section-header {
            background-color: #4472c4;
            color: white;
            font-weight: bold;
            text-align: center;
        }
        
        .sub-header {
            background-color: #8faadc;
            color: white;
            font-weight: bold;
        }
        
        .calculation {
            background-color: #f2f2f2;
        }
        
        .total-row {
            background-color: #718096;
            font-weight: bold;
        }
        
        .notes {
            font-style: italic;
            color: #7f7f7f;
            font-size: 11px;
        }
        
        .project-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            background-color: #4472c4;
            color: white;
            padding: 8px;
            border: 1px solid #a0a0a0;
        }
        
        .number {
            text-align: left;
            direction: ltr;
        }
        
        .currency-note {
            text-align: left;
            direction: ltr;
            font-size: 11px;
            color: #666;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="currency-note">جميع المبالغ (ر.س)</div>
    
    <div class="excel-container">
        <div class="project-title">موازنة مشروع الفرسان بالرياض</div>
        
        <table class="excel-table">
            <!-- معلومات المشروع -->
            <tr>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td colspan="3" style="text-align: center; border: none;">( بعد التدرج )</td>
                <td colspan="4" style="border: none;"></td>
            </tr>
            <tr>
                <td>معلومات المشروع</td>
                <td>النوع</td>
                <td>فيلا</td>
                <td>تاون هاوس</td>
                <td>شقة</td>
                <td>اجمالي عدد الوحدات</td>
                <td>اجمالي قيمة المشروع</td>
                <td>اجمالي اشهر البيع</td>
                <td>نسبة التكاليف المخطط لها</td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td>العدد</td>
                <td class="number">1,243</td>
                <td class="number">148</td>
                <td class="number">375</td>
                <td class="number">1,766</td>
                <td class="number">1,578,500,000.00</td>
                <td class="number">40</td>
                <td class="number">2%</td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td>متوسط السعر</td>
                <td class="number">1,000,000</td>
                <td class="number">1,000,000</td>
                <td class="number">500,000</td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td>الإجمالي</td>
                <td class="number">1,243,000,000</td>
                <td class="number">148,000,000</td>
                <td class="number">187,500,000</td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 15px;"></td></tr>
            
            <!-- تكلفة تسويق ومبيعات المشروع - ملخص -->
            <tr class="section-header">
                <td colspan="2">تكلفة تسويق ومبيعات المشروع - ملخص</td>
                <td colspan="3" style="border: none;"></td>
                <td colspan="4" style="text-align: center;">جميع المبالغ (ر.س)</td>
            </tr>
            <tr>
                <td>ملخص البنود الأساسية</td>
                <td>1- تكلفة المواد والتجهيزات التسويقية الثابته</td>
                <td>2- تكلفة تسويق المشروع الدورية</td>
                <td>3- التكلفة الشهرية لموظفين المبيعات واستشارات شركة كيان وشركة مسار</td>
                <td>4- المصاريف العمومية الدورية</td>
                <td>إجمالي التكلفة قبل عمولات البيع (1+2+3+4)</td>
                <td>يضاف عمولات البيع (% إلى إجمالي مبيعات المشروع)</td>
                <td>إجمالي التكلفة</td>
            </tr>
            <tr>
                <td style="border: none;"></td>
                <td class="notes">وتشمل: الهوية والمطبوعات/ نظام مبيعات والموقع الكتروني/ تصاميم 3D ومجسمات العرض/وغير ذلك من تجهيزات</td>
                <td class="notes">وتشمل: المواد والوسائل التسويقية الدورية (مثل الحملات الاعلانية /اشتراكات منصات وانظمة أخرى/ مؤثرين وإنتاج فني) + مرتبات موظفي التسويق الدورية</td>
                <td class="notes">وتشمل: مرتبات موظفي البيع الدورية + مرتبات مستشاريي البيع الدورية</td>
                <td class="notes">وتشمل: تشغيل مركز المبيعات وانتقالات + المصاريف العمومية الدورية</td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr class="calculation">
                <td>إجمالي التكلفة</td>
                <td class="number">45</td>
                <td class="number">840</td>
                <td class="number">560</td>
                <td class="number">40</td>
                <td class="number">1,485</td>
                <td class="number">10,596</td>
                <td class="number">12,081</td>
            </tr>
            <tr class="calculation">
                <td>% إجمالي المبيعات</td>
                <td class="number">0.00%</td>
                <td class="number">0.00%</td>
                <td class="number">0.00%</td>
                <td class="number">0.00%</td>
                <td class="number">0.00%</td>
                <td class="number">0.00%</td>
                <td class="number">0.00%</td>
            </tr>
            <tr class="calculation">
                <td>متوسط التكلفة الشهريه</td>
                <td>-</td>
                <td class="number">21</td>
                <td class="number">14</td>
                <td class="number">1</td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 15px;"></td></tr>
            
            <!-- تكلفة تسويق ومبيعات المشروع - تفصيلي -->
            <tr class="section-header">
                <td colspan="9">تكلفة تسويق ومبيعات المشروع - تفصيلي</td>
            </tr>
            
            <!-- 1- تكلفة المواد والتجهيزات التسويقية الثابته -->
            <tr class="sub-header">
                <td colspan="2">1- تكلفة المواد والتجهيزات التسويقية الثابته</td>
                <td colspan="4" style="border: none;"></td>
                <td colspan="3" style="text-align: center;">جميع المبالغ (ر.س)</td>
            </tr>
            <tr>
                <td>البند</td>
                <td colspan="2">طريقة الدفع</td>
                <td colspan="3">ملاحظات</td>
                <td colspan="3">إجمالي التكلفة</td>
            </tr>
            <tr>
                <td>هوية</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">0</td>
            </tr>
            <tr>
                <td>تأسيس موقع الكتروني المشروع</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">1</td>
            </tr>
            <tr>
                <td>الافتتاح</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">2</td>
            </tr>
            <tr>
                <td>مطبوعات</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">3</td>
            </tr>
            <tr>
                <td>النظام</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">4</td>
            </tr>
            <tr>
                <td>3d movie</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">5</td>
            </tr>
            <tr>
                <td>Interior design</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">6</td>
            </tr>
            <tr>
                <td>أخرى</td>
                <td colspan="2">تدفع على كذا دفعة حسب الحاجة</td>
                <td colspan="3" class="notes">مثل لوحات إعلانية بالشوارع وغيرها</td>
                <td colspan="3" class="number">7</td>
            </tr>
            <tr>
                <td>أجهزة</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">8</td>
            </tr>
            <tr>
                <td>مجسمات عرض</td>
                <td colspan="2">مرة واحدة في بداية المشروع</td>
                <td colspan="3"></td>
                <td colspan="3" class="number">9</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي تكلفة المواد والتجهيزات التسويقية الثابته</td>
                <td colspan="5"></td>
                <td colspan="3" class="number">45</td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 10px;"></td></tr>
            
            <!-- 2- تكلفة تسويق المشروع الدورية -->
            <tr class="sub-header">
                <td colspan="2">2- تكلفة تسويق المشروع الدورية</td>
                <td colspan="5" style="border: none;"></td>
                <td colspan="2" style="text-align: center;">جميع المبالغ (ر.س)</td>
            </tr>
            <tr class="sub-header">
                <td colspan="2">تكلفة المواد والوسائل التسويقية الدورية</td>
                <td colspan="5" style="border: none;"></td>
                <td colspan="2" style="border: none;"></td>
            </tr>
            <tr>
                <td>البند</td>
                <td>طريقة الدفع</td>
                <td colspan="3" style="border: none;"></td>
                <td>المبلغ الشهري</td>
                
                <td>ملاحظات</td>
                <td colspan="2">إجمالي التكلفة</td>
            </tr>
            <tr>
                <td>الحملات الاعلانية</td>
                <td>مبلغ شهري</td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">1</td>
                <td></td>
                <td colspan="2" class="number">40</td>
            </tr>
            <tr>
                <td>اشتراكات وانظمة اخرى</td>
                <td>مبلغ شهري</td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">2</td>
                <td></td>
                <td colspan="2" class="number">80</td>
            </tr>
            <tr>
                <td>انتاج فني</td>
                <td>مبلغ شهري</td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">3</td>
                <td></td>
                <td colspan="2" class="number">120</td>
            </tr>
            <tr>
                <td>عروض استرداد نقدي للعملاء</td>
                <td>يدفع على فترات طوال مدة المشروع (ليس مبلغ ثابت)</td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">4</td>
                <td></td>
                <td colspan="2" class="number">160</td>
            </tr>
            <tr>
                <td>معارض وفعاليات</td>
                <td>يدفع على فترات طوال مدة المشروع (ليس مبلغ ثابت)</td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">5</td>
                <td></td>
                <td colspan="2" class="number">200</td>
            </tr>
            <tr>
                <td>مؤثرين</td>
                <td>يدفع على فترات طوال مدة المشروع (ليس مبلغ ثابت)</td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">6</td>
                <td></td>
                <td colspan="2" class="number">240</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي البند</td>
                <td></td>
                <td colspan="3" style="border: none;"></td>
                <td class="number">21</td>
                <td></td>
                <td colspan="2" class="number">840</td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 10px;"></td></tr>
            
            <!-- 3- التكلفة الشهرية لموظفين المبيعات واستشارات شركة كيان وشركة مسار -->
            <tr class="sub-header">
                <td colspan="9">3- التكلفة الشهرية لموظفين المبيعات واستشارات شركة كيان وشركة مسار</td>
            </tr>
            <tr class="sub-header">
                <td colspan="2">تكلفة مرتبات موظفي المبيعات الدورية</td>
                <td colspan="6" style="border: none;"></td>
                <td style="text-align: center;">جميع المبالغ (ر.س)</td>
            </tr>
            <tr>
                <td>البند</td>
                <td>طريقة الدفع</td>
                <td>راتب الموظف</td>
                <td>عدد الموظفين</td>
                <td>اجمالي التكلفة الشهرية</td>
                <td>ملاحظات</td>
                <td colspan="3">إجمالي التكلفة لمدة المشروع</td>
            </tr>
            <tr>
                <td>مشرف مبيعات</td>
                <td>مبلغ شهري</td>
                <td class="number">1</td>
                <td class="number">1</td>
                <td class="number">1</td>
                <td></td>
                <td colspan="3" class="number">40</td>
            </tr>
            <tr>
                <td>ممثل مبيعات</td>
                <td>مبلغ شهري</td>
                <td class="number">1</td>
                <td class="number">4</td>
                <td class="number">4</td>
                <td></td>
                <td colspan="3" class="number">160</td>
            </tr>
            <tr>
                <td>متابع عقود</td>
                <td>مبلغ شهري</td>
                <td class="number">1</td>
                <td class="number">1</td>
                <td class="number">1</td>
                <td></td>
                <td colspan="3" class="number">40</td>
            </tr>
            <tr>
                <td>مشرف مبيعات هاتفية</td>
                <td>مبلغ شهري</td>
                <td class="number">1</td>
                <td class="number">1</td>
                <td class="number">1</td>
                <td></td>
                <td colspan="3" class="number">40</td>
            </tr>
            <tr>
                <td>كول سنتر</td>
                <td>مبلغ شهري</td>
                <td class="number">1</td>
                <td class="number">4</td>
                <td class="number">4</td>
                <td></td>
                <td colspan="3" class="number">160</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي</td>
                <td></td>
                <td></td>
                <td class="number">11</td>
                <td class="number">11</td>
                <td></td>
                <td colspan="3" class="number">440</td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 10px;"></td></tr>
            
            <!-- تكلفة شركات الاستشارات -->
            <tr class="sub-header">
                <td colspan="2">تكلفة شركات الاستشارات</td>
                <td colspan="6" style="border: none;"></td>
                <td style="text-align: center;">جميع المبالغ (ر.س)</td>
            </tr>
            <tr>
                <td>البند</td>
                <td>طريقة الدفع</td>
                <td colspan="3">ملاحظات</td>
                <td>المبلغ الشهري</td>
                <td colspan="3">إجمالي التكلفة</td>
            </tr>
            <tr>
                <td>شركة الكيان المتحدة</td>
                <td>مبلغ شهري</td>
                <td colspan="3"></td>
                <td class="number">1</td>
                <td colspan="3" class="number">40</td>
            </tr>
            <tr>
                <td>شركة دار الارجوان (مسار)</td>
                <td>مبلغ شهري</td>
                <td colspan="3"></td>
                <td class="number">2</td>
                <td colspan="3" class="number">80</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي</td>
                <td></td>
                <td colspan="3"></td>
                <td class="number">3</td>
                <td colspan="3" class="number">120</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي تكلفة موظفين المبيعات وشركات الاستشارات</td>
                <td></td>
                <td colspan="3"></td>
                <td class="number">14</td>
                <td colspan="3" class="number">560</td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 10px;"></td></tr>
            
            <!-- 4- المصاريف العمومية الدورية -->
            <tr class="sub-header">
                <td colspan="2">4- المصاريف العمومية الدورية</td>
                <td colspan="6" style="border: none;"></td>
                <td style="border: none;"></td>
            </tr>
            <tr>
                <td>البند</td>
                <td>طريقة الدفع</td>
                <td colspan="4">ملاحظات</td>
                <td>المبلغ الشهري</td>
                <td colspan="2">إجمالي التكلفة</td>
            </tr>
            <tr>
                <td>مصاريف تشغيلية لوجستية وتشغيل مراكز ومكاتب بيع</td>
                <td>مبلغ شهري تقريبي</td>
                <td colspan="4"></td>
                <td class="number">1</td>
                <td colspan="2" class="number">40</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي</td>
                <td></td>
                <td colspan="4"></td>
                <td class="number">1</td>
                <td colspan="2" class="number">40</td>
            </tr>
            
            <!-- مسافات -->
            <tr><td colspan="9" style="border: none; height: 15px;"></td></tr>
            
            <!-- توزيع العمولات -->
            <tr class="sub-header">
                <td colspan="2">توزيع العمولات</td>
                <td colspan="5" style="border: none;"></td>
                <td colspan="2" style="text-align: center;">جميع المبالغ (ر.س)</td>
            </tr>
            <tr>
                <td>البند</td>
                <td>طريقة الدفع</td>
                <td colspan="3">ملاحظات</td>
                <td>المبلغ للوحدة</td>
                <td colspan="3">إجمالي التكلفة (ر.س)</td>
            </tr>
            <tr>
                <td>استشاري المبيعات</td>
                <td>الوحده المباعه</td>
                <td colspan="3"></td>
                <td class="number">1</td>
                <td colspan="3" class="number">1,766</td>
            </tr>
            <tr>
                <td>مركز الاتصال</td>
                <td>الوحده المباعه</td>
                <td colspan="3"></td>
                <td class="number">1</td>
                <td colspan="3" class="number">1,766</td>
            </tr>
            <tr>
                <td>عمولة مدير ومشرف المبيعات</td>
                <td>الوحده المباعه</td>
                <td colspan="3" class="notes">20%</td>
                <td class="number">1</td>
                <td colspan="3" class="number">1,766</td>
            </tr>
            <tr>
                <td>عمولة قسم التسويق</td>
                <td>الوحده المباعه</td>
                <td colspan="3"></td>
                <td class="number">1</td>
                <td colspan="3" class="number">1,766</td>
            </tr>
            <tr>
                <td>عمولة شركة الكيان المتحدة</td>
                <td>الوحده المباعه</td>
                <td colspan="3"></td>
                <td class="number">1</td>
                <td colspan="3" class="number">1,766</td>
            </tr>
            <tr>
                <td>عمولة شركة دار الارجوان (مسار)</td>
                <td>الوحده المباعه</td>
                <td colspan="3"></td>
                <td class="number">1</td>
                <td colspan="3" class="number">1,766</td>
            </tr>
            <tr class="total-row">
                <td>إجمالي</td>
                <td></td>
                <td colspan="3"></td>
                <td class="number">6</td>
                <td colspan="3" class="number">10,596</td>
            </tr>
        </table>
    </div>
</body>
</html>