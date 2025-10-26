<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نموذج تكلفة مشروع للنظام</title>
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34495e;
            --success-color: #27ae60;
            --warning-color: #f39c12;
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary-color), var(--dark-color));
            color: white;
            padding: 20px 0;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        
        .project-info {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }
        
        .info-item {
            background-color: var(--light-color);
            padding: 15px;
            border-radius: 6px;
            border-right: 4px solid var(--secondary-color);
        }
        
        .info-label {
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 5px;
        }
        
        .info-value {
            font-size: 18px;
            color: var(--primary-color);
        }
        
        .section {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .section-title {
            color: var(--primary-color);
            border-bottom: 2px solid var(--light-color);
            padding-bottom: 10px;
            margin-bottom: 20px;
            font-size: 22px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: right;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: var(--light-color);
            color: var(--dark-color);
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .summary-table {
            background-color: var(--light-color);
            border-radius: 6px;
            overflow: hidden;
        }
        
        .summary-table th {
            background-color: var(--secondary-color);
            color: white;
        }
        
        .cost-breakdown {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .cost-card {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-top: 4px solid var(--secondary-color);
        }
        
        .cost-title {
            font-weight: bold;
            color: var(--dark-color);
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .cost-value {
            font-size: 24px;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .cost-percentage {
            color: var(--accent-color);
            font-weight: bold;
        }
        
        .notes {
            font-style: italic;
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
        
        footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            color: #7f8c8d;
            font-size: 14px;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .cost-breakdown {
                grid-template-columns: 1fr;
            }
            
            table {
                font-size: 14px;
            }
            
            th, td {
                padding: 8px 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <div class="header-content">
                <div>
                    <h1>نموذج تكلفة مشروع للنظام</h1>
                    <p>موازنة مشروع الفرسان بالرياض</p>
                </div>
                <div>
                    <p>جميع المبالغ (ر.س)</p>
                </div>
            </div>
        </header>
        
        <section class="project-info">
            <h2 class="section-title">معلومات المشروع</h2>
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">نوع الوحدات</div>
                    <div class="info-value">فيلا / تاون هاوس / شقة</div>
                </div>
                <div class="info-item">
                    <div class="info-label">إجمالي عدد الوحدات</div>
                    <div class="info-value">1,766 وحدة</div>
                </div>
                <div class="info-item">
                    <div class="info-label">إجمالي قيمة المشروع</div>
                    <div class="info-value">1,743,000,000 ر.س</div>
                </div>
                <div class="info-item">
                    <div class="info-label">أشهر البيع</div>
                    <div class="info-value">40 شهر</div>
                </div>
            </div>
        </section>
        
        <section class="section">
            <h2 class="section-title">تكلفة تسويق ومبيعات المشروع - ملخص</h2>
            
            <table class="summary-table">
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>التكلفة (ر.س)</th>
                        <th>نسبة إجمالي المبيعات</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>المواد والتجهيزات التسويقية الثابتة</td>
                        <td>45,000 ر.س</td>
                        <td>0.0026%</td>
                    </tr>
                    <tr>
                        <td>تكلفة تسويق المشروع الدورية</td>
                        <td>756,000 ر.س</td>
                        <td>0.0434%</td>
                    </tr>
                    <tr>
                        <td>تكلفة موظفي المبيعات والاستشارات</td>
                        <td>1,680,000 ر.س</td>
                        <td>0.0964%</td>
                    </tr>
                    <tr>
                        <td>المصاريف العمومية الدورية</td>
                        <td>40,000 ر.س</td>
                        <td>0.0023%</td>
                    </tr>
                    <tr>
                        <td><strong>إجمالي التكلفة قبل عمولات البيع</strong></td>
                        <td><strong>2,521,000 ر.س</strong></td>
                        <td><strong>0.1447%</strong></td>
                    </tr>
                    <tr>
                        <td>عمولات البيع</td>
                        <td>10,596,000 ر.س</td>
                        <td>0.608%</td>
                    </tr>
                    <tr>
                        <td><strong>إجمالي التكلفة</strong></td>
                        <td><strong>13,117,000 ر.س</strong></td>
                        <td><strong>0.7527%</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <div class="cost-breakdown">
                <div class="cost-card">
                    <div class="cost-title">متوسط التكلفة الشهرية للتسويق</div>
                    <div class="cost-value">18,900 ر.س</div>
                </div>
                <div class="cost-card">
                    <div class="cost-title">متوسط التكلفة الشهرية للمبيعات</div>
                    <div class="cost-value">42,000 ر.س</div>
                </div>
                <div class="cost-card">
                    <div class="cost-title">متوسط التكلفة الشهرية للمصاريف</div>
                    <div class="cost-value">1,000 ر.س</div>
                </div>
            </div>
        </section>
        
        <section class="section">
            <h2 class="section-title">تفاصيل التكاليف</h2>
            
            <h3 style="margin: 20px 0 10px; color: var(--dark-color);">1- تكلفة المواد والتجهيزات التسويقية الثابتة</h3>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>طريقة الدفع</th>
                        <th>ملاحظات</th>
                        <th>التكلفة (ر.س)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>هوية</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>5,000</td>
                    </tr>
                    <tr>
                        <td>تأسيس موقع الكتروني المشروع</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>15,000</td>
                    </tr>
                    <tr>
                        <td>الافتتاح</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>50,000</td>
                    </tr>
                    <tr>
                        <td>مطبوعات</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>20,000</td>
                    </tr>
                    <tr>
                        <td>النظام</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>30,000</td>
                    </tr>
                    <tr>
                        <td>3D Movie</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>80,000</td>
                    </tr>
                    <tr>
                        <td>Interior Design</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>100,000</td>
                    </tr>
                    <tr>
                        <td>أخرى</td>
                        <td>تدفع على كذا دفعة حسب الحاجة</td>
                        <td>مثل لوحات إعلانية بالشوارع وغيرها</td>
                        <td>50,000</td>
                    </tr>
                    <tr>
                        <td>أجهزة</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>40,000</td>
                    </tr>
                    <tr>
                        <td>مجسمات عرض</td>
                        <td>مرة واحدة في بداية المشروع</td>
                        <td></td>
                        <td>75,000</td>
                    </tr>
                    <tr>
                        <td><strong>الإجمالي</strong></td>
                        <td></td>
                        <td></td>
                        <td><strong>465,000 ر.س</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <h3 style="margin: 30px 0 10px; color: var(--dark-color);">2- تكلفة تسويق المشروع الدورية</h3>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>طريقة الدفع</th>
                        <th>المبلغ الشهري (ر.س)</th>
                        <th>ملاحظات</th>
                        <th>إجمالي التكلفة (ر.س)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>الحملات الإعلانية</td>
                        <td>مبلغ شهري</td>
                        <td>10,000</td>
                        <td></td>
                        <td>400,000</td>
                    </tr>
                    <tr>
                        <td>اشتراكات وأنظمة أخرى</td>
                        <td>مبلغ شهري</td>
                        <td>3,000</td>
                        <td></td>
                        <td>120,000</td>
                    </tr>
                    <tr>
                        <td>إنتاج فني</td>
                        <td>مبلغ شهري</td>
                        <td>2,000</td>
                        <td></td>
                        <td>80,000</td>
                    </tr>
                    <tr>
                        <td>عروض استرداد نقدي للعملاء</td>
                        <td>يدفع على فترات طوال مدة المشروع</td>
                        <td>1,500</td>
                        <td></td>
                        <td>60,000</td>
                    </tr>
                    <tr>
                        <td>معارض وفعاليات</td>
                        <td>يدفع على فترات طوال مدة المشروع</td>
                        <td>1,500</td>
                        <td></td>
                        <td>60,000</td>
                    </tr>
                    <tr>
                        <td>مؤثرين</td>
                        <td>يدفع على فترات طوال مدة المشروع</td>
                        <td>900</td>
                        <td></td>
                        <td>36,000</td>
                    </tr>
                    <tr>
                        <td><strong>الإجمالي</strong></td>
                        <td></td>
                        <td><strong>18,900 ر.س</strong></td>
                        <td></td>
                        <td><strong>756,000 ر.س</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <h3 style="margin: 30px 0 10px; color: var(--dark-color);">3- التكلفة الشهرية لموظفي المبيعات والاستشارات</h3>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>طريقة الدفع</th>
                        <th>عدد الموظفين</th>
                        <th>الراتب الشهري (ر.س)</th>
                        <th>التكلفة الشهرية (ر.س)</th>
                        <th>إجمالي التكلفة (ر.س)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>مشرف مبيعات</td>
                        <td>مبلغ شهري</td>
                        <td>1</td>
                        <td>8,000</td>
                        <td>8,000</td>
                        <td>320,000</td>
                    </tr>
                    <tr>
                        <td>ممثل مبيعات</td>
                        <td>مبلغ شهري</td>
                        <td>4</td>
                        <td>6,000</td>
                        <td>24,000</td>
                        <td>960,000</td>
                    </tr>
                    <tr>
                        <td>متابع عقود</td>
                        <td>مبلغ شهري</td>
                        <td>1</td>
                        <td>5,000</td>
                        <td>5,000</td>
                        <td>200,000</td>
                    </tr>
                    <tr>
                        <td>مشرف مبيعات هاتفية</td>
                        <td>مبلغ شهري</td>
                        <td>1</td>
                        <td>7,000</td>
                        <td>7,000</td>
                        <td>280,000</td>
                    </tr>
                    <tr>
                        <td>كول سنتر</td>
                        <td>مبلغ شهري</td>
                        <td>4</td>
                        <td>4,500</td>
                        <td>18,000</td>
                        <td>720,000</td>
                    </tr>
                    <tr>
                        <td><strong>إجمالي الموظفين</strong></td>
                        <td></td>
                        <td><strong>11</strong></td>
                        <td></td>
                        <td><strong>62,000 ر.س</strong></td>
                        <td><strong>2,480,000 ر.س</strong></td>
                    </tr>
                    <tr>
                        <td>شركة الكيان المتحدة</td>
                        <td>مبلغ شهري</td>
                        <td>-</td>
                        <td>15,000</td>
                        <td>15,000</td>
                        <td>600,000</td>
                    </tr>
                    <tr>
                        <td>شركة دار الأرجوان (مسار)</td>
                        <td>مبلغ شهري</td>
                        <td>-</td>
                        <td>10,000</td>
                        <td>10,000</td>
                        <td>400,000</td>
                    </tr>
                    <tr>
                        <td><strong>الإجمالي الكامل</strong></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><strong>87,000 ر.س</strong></td>
                        <td><strong>3,480,000 ر.س</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <h3 style="margin: 30px 0 10px; color: var(--dark-color);">4- المصاريف العمومية الدورية</h3>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>طريقة الدفع</th>
                        <th>ملاحظات</th>
                        <th>المبلغ الشهري (ر.س)</th>
                        <th>إجمالي التكلفة (ر.س)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>مصاريف تشغيلية لوجستية وتشغيل مراكز ومكاتب بيع</td>
                        <td>مبلغ شهري تقريبي</td>
                        <td></td>
                        <td>1,000</td>
                        <td>40,000</td>
                    </tr>
                    <tr>
                        <td><strong>الإجمالي</strong></td>
                        <td></td>
                        <td></td>
                        <td><strong>1,000 ر.س</strong></td>
                        <td><strong>40,000 ر.س</strong></td>
                    </tr>
                </tbody>
            </table>
            
            <h3 style="margin: 30px 0 10px; color: var(--dark-color);">توزيع العمولات</h3>
            <table>
                <thead>
                    <tr>
                        <th>البند</th>
                        <th>طريقة الدفع</th>
                        <th>ملاحظات</th>
                        <th>المبلغ للوحدة (ر.س)</th>
                        <th>إجمالي التكلفة (ر.س)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>استشاري المبيعات</td>
                        <td>الوحدة المباعة</td>
                        <td></td>
                        <td>2,000</td>
                        <td>3,532,000</td>
                    </tr>
                    <tr>
                        <td>مركز الاتصال</td>
                        <td>الوحدة المباعة</td>
                        <td></td>
                        <td>500</td>
                        <td>883,000</td>
                    </tr>
                    <tr>
                        <td>عمولة مدير ومشرف المبيعات</td>
                        <td>الوحدة المباعة</td>
                        <td>20%</td>
                        <td>400</td>
                        <td>706,400</td>
                    </tr>
                    <tr>
                        <td>عمولة قسم التسويق</td>
                        <td>الوحدة المباعة</td>
                        <td></td>
                        <td>1,000</td>
                        <td>1,766,000</td>
                    </tr>
                    <tr>
                        <td>عمولة شركة الكيان المتحدة</td>
                        <td>الوحدة المباعة</td>
                        <td></td>
                        <td>1,500</td>
                        <td>2,649,000</td>
                    </tr>
                    <tr>
                        <td>عمولة شركة دار الأرجوان (مسار)</td>
                        <td>الوحدة المباعة</td>
                        <td></td>
                        <td>1,000</td>
                        <td>1,766,000</td>
                    </tr>
                    <tr>
                        <td><strong>الإجمالي</strong></td>
                        <td></td>
                        <td></td>
                        <td><strong>6,400 ر.س</strong></td>
                        <td><strong>11,302,400 ر.س</strong></td>
                    </tr>
                </tbody>
            </table>
        </section>
        
        <footer>
            <p>تم إنشاء هذا النموذج بناءً على بيانات مشروع الفرسان بالرياض</p>
            <p>جميع المبالغ بالريال السعودي (ر.س)</p>
        </footer>
    </div>
</body>
</html>