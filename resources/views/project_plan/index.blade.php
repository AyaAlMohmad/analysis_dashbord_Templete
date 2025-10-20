<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>خطة متابعة مشروع أزيان جدة (تطوير بنية فوقية)</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        body {
            background-color: white;
            padding: 10px;
            direction: rtl;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            overflow-x: auto;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding: 12px;
            background: #2F5496;
            color: white;
            border-radius: 4px;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 2px solid #2F5496;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #A6A6A6;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #2F5496;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            font-size: 11px;
            border: 1px solid #A6A6A6;
        }

        /* ألوان حسب الحالة - مطابقة للصورة */
        .status-completed {
            background-color: #92D050 !important;
            /* أخضر */
        }

        .status-inprogress {
            background-color: #FFC000 !important;
            /* أصفر */
        }

        .status-notstarted {
            background-color: #FF7C80 !important;
            /* أحمر */
        }

        .section-title {
            background-color: #B4C6E7;
            /* أزرق فاتح للعناوين الرئيسية */
            font-weight: bold;
            text-align: center;
            font-size: 12px;
        }

        .sub-section {
            background-color: #D9E1F2;
            /* أزرق أفتح للعناوين الفرعية */
            font-weight: bold;
            text-align: right;
        }

        .notes-cell {
            text-align: right;
            max-width: 250px;
            white-space: normal;
            font-size: 10px;
        }

        /* تنسيق الخلايا النصية */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* أعمدة محددة - البند كبير قد المتطلبات */
        .col-id {
            width: 4%;
            min-width: 30px;
        }

        .col-item {
            width: 20%;
            text-align: right;
            min-width: 200px;
        }

        .col-requirements {
            width: 20%;
            text-align: right;
            min-width: 200px;
        }

        .col-start,
        .col-end,
        .col-updated {
            width: 7%;
            min-width: 80px;
        }

        .col-duration {
            width: 6%;
            min-width: 60px;
        }

        .col-department {
            width: 10%;
            text-align: right;
            min-width: 100px;
        }

        .col-responsible {
            width: 12%;
            text-align: right;
            min-width: 120px;
        }

        .col-notes {
            width: 14%;
            text-align: right;
            min-width: 140px;
        }

        /* تحسين المظهر للشاشات الصغيرة */
        @media (max-width: 1200px) {
            table {
                font-size: 10px;
            }

            th,
            td {
                padding: 4px 6px;
            }
        }

        /* جعل الصفوف متماثلة الارتفاع */
        tr {
            height: 35px;
        }

        /* تنسيق البنود الرئيسية */
        .main-item {
            font-weight: bold;
            background-color: #E6E6E6;
        }

        /* تنسيق البنود الفرعية */
        .sub-item {
            background-color: #F2F2F2;
        }

        /* إخفاء الحدود بين الخلايا المدمجة */
        .hidden-border {
            border-top: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>خطة متابعة مشروع أزيان جدة (تطوير بنية فوقية)</h1>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="col-id">م</th>
                    <th class="col-item">البند</th>
                    <th class="col-requirements">المتطلبات</th>
                    <th class="col-start">البداية</th>
                    <th class="col-end">النهاية</th>
                    <th class="col-updated">النهاية المحدثة</th>
                    <th class="col-duration">المدة</th>
                    <th class="col-department">الإدارة</th>
                    <th class="col-responsible">المسؤول</th>
                    <th class="col-notes">ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                <!-- ما قبل الترسية -->
                <tr class="section-title">
                    <td colspan="10">ما قبل الترسية</td>
                </tr>

                <tr class="status-notstarted main-item">
                    <td>1</td>
                    <td class="col-item text-right" rowspan="1">استقبال الدعوات</td>
                    <td class="col-requirements text-right"></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td>حسب مدة الطرح وتقديم العروض</td>
                    <td class="col-department text-right">-</td>
                    <td class="col-responsible text-right">-</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="main-item">
                    <td>2</td>
                    <td class="col-item text-right" rowspan="3">الدراسة الفنية</td>
                    <td class="col-requirements text-right">معاينة الموقع</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">تقرير فنى بحالة الخدمات بالموقع - الرفع المساحي</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">تقرير تربة</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="main-item">
                    <td>3</td>
                    <td class="col-item text-right" rowspan="3">الدراسة المالية</td>
                    <td class="col-requirements text-right">بنية فوقية</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">بنية تحتية</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">دراسة السوق</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted main-item">
                    <td>4</td>
                    <td class="col-item text-right" rowspan="1">تقديم العرض الفني والمالي</td>
                    <td class="col-requirements text-right"></td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- الترسية وتوقيع الاتفاقية -->
                <tr class="section-title">
                    <td colspan="10">الترسية وتوقيع الاتفاقية</td>
                </tr>

                <!-- اعتماد التصاميم -->
                <tr class="main-item">
                    <td>5</td>
                    <td class="col-item text-right" rowspan="3">اعتماد التصاميم من الوطنية وإتمام</td>
                    <td class="col-requirements text-right">النماذج</td>
                    <td>2025-01-01</td>
                    <td>2025-02-01</td>
                    <td class="status-completed">تم</td>
                    <td>31</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">الموقع العام</td>
                    <td>2025-01-01</td>
                    <td>2025-02-01</td>
                    <td class="status-completed">تم</td>
                    <td>31</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">جدول التخصيص</td>
                    <td>2025-01-01</td>
                    <td>2025-02-01</td>
                    <td class="status-completed">تم</td>
                    <td>31</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- تجهيز المبيعات -->
                <tr class="main-item">
                    <td>6</td>
                    <td class="col-item text-right" rowspan="7">تجهيز المبيعات</td>
                    <td class="col-requirements text-right">ماكيت المشروع</td>
                    <td>2025-08-01</td>
                    <td>2025-08-28</td>
                    <td class="status-completed">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">بروشور المشروع</td>
                    <td>2025-08-01</td>
                    <td>2025-08-28</td>
                    <td class="status-completed">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">فيديو المشروع</td>
                    <td>2025-08-01</td>
                    <td>2025-08-28</td>
                    <td class="status-completed">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">مواد تسويقية (مطبوعات-مواقع إلكترونية)</td>
                    <td>2025-08-01</td>
                    <td>2025-08-28</td>
                    <td class="status-completed">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">تصميم بوث المبيعات</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-inprogress sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">تنفيذ بوث المبيعات</td>
                    <td>2025-08-01</td>
                    <td>2025-08-25</td>
                    <td>-</td>
                    <td>24</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">التوظيف</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- استخراج رخصة البيع الابتدائية -->
                <tr class="main-item">
                    <td>7</td>
                    <td class="col-item text-right" rowspan="5">استخراج رخصة البيع الابتدائية</td>
                    <td class="col-requirements text-right">حساب الضمان</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">تعاقد مع الاستشاري</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">تعاقد مع المحاسب القانوني</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">دراسة الجدوى</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-completed sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">دفع رسوم الرخصة</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="status-completed">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- اطلاق البيع -->
                <tr class="status-inprogress main-item">
                    <td>8</td>
                    <td class="col-item text-right" rowspan="1">اطلاق البيع</td>
                    <td class="col-requirements text-right">بعد الانتهاء من تجهيز المبيعات</td>
                    <td>2025-09-01</td>
                    <td></td>
                    <td>-</td>
                    <td>الى نهاية المشروع</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- التأمين -->
                <tr class="status-notstarted main-item">
                    <td>9</td>
                    <td class="col-item text-right" rowspan="1">التأمين</td>
                    <td class="col-requirements text-right"></td>
                    <td>2025-12-01</td>
                    <td>2026-03-31</td>
                    <td>-</td>
                    <td>120</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">متطلب لرخص البناء</td>
                </tr>

                <!-- رخص البناء -->
                <tr class="main-item">
                    <td>10</td>
                    <td class="col-item text-right" rowspan="2">رخص البناء</td>
                    <td class="col-requirements text-right">اعتماد المخطط العام</td>
                    <td>TBC BY NHC</td>
                    <td>TBC BY NHC</td>
                    <td>-</td>
                    <td>TBC BY NHC</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell">بعد اعتماد المخطط العام</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">التأمين</td>
                    <td>2025-12-01</td>
                    <td>2026-04-30</td>
                    <td>-</td>
                    <td>150</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- استخراج رخصة البيع النهائية -->
                <tr class="main-item">
                    <td>11</td>
                    <td class="col-item text-right" rowspan="2">استخراج رخصة البيع النهائية</td>
                    <td class="col-requirements text-right">رخص البناء</td>
                    <td>2025-12-01</td>
                    <td>2026-04-30</td>
                    <td>-</td>
                    <td>150</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">تم إصدار رخصة البيع النهائية</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">اعتماد المخطط العام</td>
                    <td>TBC BY NHC</td>
                    <td>TBC BY NHC</td>
                    <td>-</td>
                    <td>TBC BY NHC</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- التنفيذ -->
                <tr class="section-title">
                    <td colspan="10">التنفيذ</td>
                </tr>

                <!-- اعتماد مخططات التنفيذية للفيلات -->
                <tr class="main-item">
                    <td>12</td>
                    <td class="col-item text-right" rowspan="5">اعتماد مخططات التنفيذية للفيلات</td>
                    <td class="col-requirements text-right">أنشائي</td>
                    <td>2025-09-17</td>
                    <td>2025-10-17</td>
                    <td>-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">معماري</td>
                    <td>2025-09-17</td>
                    <td>2025-10-17</td>
                    <td>-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">ميكانيكا</td>
                    <td>2025-09-17</td>
                    <td>2025-10-17</td>
                    <td>-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">كهرباء</td>
                    <td>2025-09-17</td>
                    <td>2025-10-17</td>
                    <td>-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="col-requirements text-right">مخططات تنسيقية</td>
                    <td>2025-09-17</td>
                    <td>2025-10-17</td>
                    <td>-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <!-- استلام الموقع -->
                <tr class="status-notstarted main-item">
                    <td>13</td>
                    <td class="col-item text-right" rowspan="1">استلام الموقع</td>
                    <td class="col-requirements text-right">استلام الموقع</td>
                    <td>2025-11-01</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="col-department text-right">الإدارة العليا</td>
                    <td class="col-responsible text-right">الإدارة العليا</td>
                    <td class="col-notes notes-cell">حسب إفادة رئيس الضاحية</td>
                </tr>

                <!-- استلام IFC DRAWINGS -->
                <tr class="status-notstarted main-item">
                    <td>14</td>
                    <td class="col-item text-right" rowspan="1"></td>
                    <td class="col-requirements text-right">استلام IFC DRAWINGS</td>
                    <td>2025-11-01</td>
                    <td>-</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">إدارة التنفيذ</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- تنفيذ البنية الفوقية -->
                <tr class="status-notstarted main-item">
                    <td>15</td>
                    <td class="col-item text-right" rowspan="1">تنفيذ البنية الفوقية (مرتبط بالبيع)</td>
                    <td class="col-requirements text-right">أعمال تنفيذ الفيلات</td>
                    <td>2025-12-01</td>
                    <td>2029-03-01</td>
                    <td>-</td>
                    <td>1186</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">إدارة التنفيذ</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- التسليم -->
                <tr class="section-title">
                    <td colspan="10">التسليم</td>
                </tr>

                <!-- جمع متطلبات الإفراغ من العملاء -->
                <tr class="status-notstarted main-item">
                    <td>16</td>
                    <td class="col-item text-right" rowspan="1">جمع متطلبات الإفراغ من العملاء</td>
                    <td class="col-requirements text-right"></td>
                    <td>2028-03-01</td>
                    <td>2029-03-01</td>
                    <td>-</td>
                    <td>365</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- فرز الصكوك -->
                <tr class="status-notstarted main-item">
                    <td>17</td>
                    <td class="col-item text-right" rowspan="1">فرز الصكوك</td>
                    <td class="col-requirements text-right"></td>
                    <td>TBC</td>
                    <td>2029-03-01</td>
                    <td>-</td>
                    <td>-</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">بعد اعتماد المخطط العام</td>
                </tr>

                <!-- توصيل الخدمات -->
                <tr class="status-notstarted main-item">
                    <td>18</td>
                    <td class="col-item text-right" rowspan="1">توصيل الخدمات</td>
                    <td class="col-requirements text-right"></td>
                    <td>2028-05-01</td>
                    <td>2029-03-01</td>
                    <td>-</td>
                    <td>304</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">إدارة التنفيذ</td>
                    <td class="col-notes notes-cell">قبل نهاية المشروع بـ 10 شهور</td>
                </tr>

                <!-- تسليم الوحدات -->
                <tr class="status-notstarted main-item">
                    <td>19</td>
                    <td class="col-item text-right" rowspan="1">تسليم الوحدات</td>
                    <td class="col-requirements text-right"></td>
                    <td>2028-11-01</td>
                    <td>2029-03-01</td>
                    <td>-</td>
                    <td>120</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- الإفراغ -->
                <tr class="status-notstarted main-item">
                    <td>20</td>
                    <td class="col-item text-right" rowspan="1">الإفراغ</td>
                    <td class="col-requirements text-right"></td>
                    <td>2029-03-01</td>
                    <td>2030-03-01</td>
                    <td>-</td>
                    <td>365</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- سداد الأرض -->
                <tr class="status-notstarted main-item">
                    <td>21</td>
                    <td class="col-item text-right" rowspan="1">سداد الأرض</td>
                    <td class="col-requirements text-right">شهري</td>
                    <td>2029-03-01</td>
                    <td>2029-07-01</td>
                    <td>-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- سداد البنية التحتية -->
                <tr class="status-notstarted main-item">
                    <td>22</td>
                    <td class="col-item text-right" rowspan="1">سداد البنية التحتية</td>
                    <td class="col-requirements text-right">شهري</td>
                    <td>2029-03-01</td>
                    <td>2029-07-01</td>
                    <td>-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- سداد رسوم الوطنية -->
                <tr class="status-notstarted main-item">
                    <td>23</td>
                    <td class="col-item text-right" rowspan="1">سداد رسوم الوطنية</td>
                    <td class="col-requirements text-right">شهري</td>
                    <td>2029-03-01</td>
                    <td>2029-07-01</td>
                    <td>-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- خدمات ما بعد البيع -->
                <tr class="status-notstarted main-item">
                    <td>24</td>
                    <td class="col-item text-right" rowspan="1">خدمات ما بعد البيع</td>
                    <td class="col-requirements text-right"></td>
                    <td>2028-11-01</td>
                    <td>2029-11-01</td>
                    <td>-</td>
                    <td>365</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- شهادة إنجاز مشروع مع وافي -->
                <tr class="status-notstarted main-item">
                    <td>25</td>
                    <td class="col-item text-right" rowspan="1">شهادة إنجاز مشروع مع وافي</td>
                    <td class="col-requirements text-right"></td>
                    <td>2029-03-01</td>
                    <td>2029-07-01</td>
                    <td>-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- شهادة إتمام بناء -->
                <tr class="status-notstarted main-item">
                    <td>26</td>
                    <td class="col-item text-right" rowspan="1">شهادة إتمام بناء</td>
                    <td class="col-requirements text-right"></td>
                    <td>2028-04-01</td>
                    <td>2029-03-01</td>
                    <td>-</td>
                    <td>334</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">يبدأ مع بداية مرحلة التنفيذ الأخيرة (متطلب لتوصيل الخدمات )</td>
                </tr>

            </tbody>
        </table>
    </div>
</body>

</html>