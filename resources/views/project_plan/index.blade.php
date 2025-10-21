@extends('layouts.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 15px;
            background: #2F5496;
            color: white;
            border-radius: 4px;
            font-size: 16px;
            position: relative;
        }

        .logo-container {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%);
        }

        .logo {
            max-height: 60px;
            max-width: 150px;
        }

        .header-content {
            margin: 0 200px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .header p {
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 2px solid #2F5496;
            font-size: 12px;
            table-layout: fixed;
        }

        th, td {
            border: 1px solid #A6A6A6;
            padding: 6px 8px;
            text-align: center;
            vertical-align: middle;
            line-height: 1.3;
        }

        th {
            background-color: #2F5496;
            color: white;
            font-weight: bold;
            position: sticky;
            top: 0;
            font-size: 12px;
            border: 1px solid #A6A6A6;
            padding: 8px 6px;
        }

        /* ألوان حسب الحالة - مطابقة للصورة */
        .status-completed {
            background-color: #92D050 !important; /* أخضر فاتح */
            color: #000;
            font-weight: bold;
        }

        .status-inprogress {
            background-color: #FFC000 !important; /* أصفر */
            color: #000;
            font-weight: bold;
        }

        .status-notstarted {
            background-color: #FFFFFF !important; /* أبيض */
            color: #000;
        }

        .section-title {
            background-color: #B4C6E7 !important; /* أزرق فاتح للعناوين الرئيسية */
            font-weight: bold;
            text-align: center;
            font-size: 13px;
            color: #000;
        }

        .notes-cell {
            text-align: right;
            max-width: 250px;
            white-space: normal;
            font-size: 11px;
        }

        /* تنسيق الخلايا النصية */
        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        /* أعمدة محددة - مطابقة للصورة */
        .col-id {
            width: 4%;
            min-width: 40px;
        }

        .col-item {
            width: 18%;
            text-align: right;
            min-width: 180px;
        }

        .col-requirements {
            width: 18%;
            text-align: right;
            min-width: 180px;
        }

        .col-start,
        .col-end,
        .col-updated {
            width: 8%;
            min-width: 90px;
        }

        .col-duration {
            width: 6%;
            min-width: 70px;
        }

        .col-department {
            width: 10%;
            text-align: right;
            min-width: 110px;
        }

        .col-responsible {
            width: 12%;
            text-align: right;
            min-width: 130px;
        }

        .col-notes {
            width: 16%;
            text-align: right;
            min-width: 160px;
        }

        /* تنسيق البنود الرئيسية */
        .main-item {
            font-weight: bold;
            background-color: #E6E6E6 !important;
        }

        /* تنسيق البنود الفرعية */
        .sub-item {
            background-color: #F2F2F2 !important;
        }

        /* تحسين مظهر الخلايا الفارغة */
        .empty-cell:before {
            content: "-";
            color: #A6A6A6;
        }

        /* تحسين مظهر التواريخ */
        .date-cell {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: normal;
        }

        /* تحسين التباين للنص */
        .status-cell {
            font-weight: bold;
        }

        /* تنسيقات الطباعة */
        @media print {
            body {
                padding: 0;
                margin: 0;
                background: white;
            }
            .container {
                max-width: 100%;
                margin: 0;
            }
            .header {
                margin-bottom: 10px;
                page-break-after: avoid;
            }
            table {
                page-break-inside: auto;
                border: 2px solid #2F5496 !important;
            }
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
            th {
                background-color: #2F5496 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .status-completed {
                background-color: #92D050 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .status-inprogress {
                background-color: #FFC000 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .section-title {
                background-color: #B4C6E7 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .main-item {
                background-color: #E6E6E6 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .sub-item {
                background-color: #F2F2F2 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }

        /* أزرار التحكم */
        .controls {
            position: fixed;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
            display: flex;
            gap: 10px;
        }

        .btn {
            background: #2F5496;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background 0.3s;
        }

        .btn:hover {
            background: #1e3a6d;
        }

        .btn-excel {
            background: #107c41;
        }

        .btn-excel:hover {
            background: #0d6635;
        }

        .btn-print {
            background: #17a2b8;
        }

        .btn-print:hover {
            background: #138496;
        }

        /* شاشة التحميل */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 18px;
            z-index: 9999;
        }

        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid white;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin-bottom: 15px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* تحسين المظهر العام */
        .container table tr {
            height: 35px;
        }

        .container table td {
            word-wrap: break-word;
            overflow: hidden;
        }
    </style>

    <div class="controls">
        <button class="btn btn-excel" onclick="exportToExcel()">📊 تصدير Excel</button>
        <button class="btn btn-print" onclick="window.print()">🖨️ طباعة</button>
    </div>

    <div class="container">
        <div class="header">
            <div class="logo-container">
                <img src="{{ asset('images/JeddahLogo.png') }}" class="logo" alt="Logo">
            </div>
            <div class="header-content">
                <h1>خطة متابعة مشروع أزيان جدة (تطوير بنية فوقية)</h1>
                <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
            </div>
        </div>

           <table id="project-table">
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
                    <td class="status-completed col-requirements text-right"></td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td rowspan="8">حسب مدة الطرح وتقديم العروض</td>
                    <td class="col-department text-right">-</td>
                    <td class="col-responsible text-right">-</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="main-item">
                    <td>2</td>
                    <td class="col-item text-right" rowspan="3">الدراسة الفنية</td>
                    <td class="status-completed col-requirements text-right">معاينة الموقع</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">تقرير فنى بحالة الخدمات بالموقع - الرفع المساحي
                    </td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">تقرير تربة</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="main-item">
                    <td>3</td>
                    <td class="col-item text-right" rowspan="3">الدراسة المالية</td>
                    <td class="status-completed col-requirements text-right">بنية فوقية</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">بنية تحتية</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">وليد عطية</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">دراسة السوق</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td></td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="status-notstarted main-item">
                    <td>4</td>
                    <td class="col-item text-right" rowspan="1">تقديم العرض الفني والمالي</td>
                    <td class="status-completed col-requirements text-right"></td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
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
                    <td class="status-completed col-requirements text-right">النماذج</td>
                    <td class="date-cell">2025-01-01</td>
                    <td class="date-cell">2025-02-01</td>
                    <td class="status-cell ">تم</td>
                    <td>31</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">الموقع العام</td>
                    <td class="date-cell">2025-01-01</td>
                    <td class="date-cell">2025-02-01</td>
                    <td class="status-cell ">تم</td>
                    <td>31</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">جدول التخصيص</td>
                    <td class="date-cell">2025-01-01</td>
                    <td class="date-cell">2025-02-01</td>
                    <td class="status-cell ">تم</td>
                    <td>31</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- تجهيز المبيعات -->
                <tr class="main-item">
                    <td>6</td>
                    <td class="col-item text-right" rowspan="7">تجهيز المبيعات</td>
                    <td class="status-completed col-requirements text-right">ماكيت المشروع</td>
                    <td class="date-cell">2025-08-01</td>
                    <td class="date-cell">2025-08-28</td>
                    <td class="status-cell ">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">بروشور المشروع</td>
                    <td class="date-cell">2025-08-01</td>
                    <td class="date-cell">2025-08-28</td>
                    <td class="status-cell ">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">فيديو المشروع</td>
                    <td class="date-cell">2025-08-01</td>
                    <td class="date-cell">2025-08-28</td>
                    <td class="status-cell ">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">مواد تسويقية (مطبوعات-مواقع إلكترونية)</td>
                    <td class="date-cell">2025-08-01</td>
                    <td class="date-cell">2025-08-28</td>
                    <td class="status-cell ">تم</td>
                    <td>27</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد - فهد عطرجي</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">تصميم بوث المبيعات</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-inprogress col-requirements text-right">تنفيذ بوث المبيعات</td>
                    <td class="date-cell">2025-08-01</td>
                    <td class="date-cell">2025-08-25</td>
                    <td class="date-cell">-</td>
                    <td>24</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">التوظيف</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- استخراج رخصة البيع الابتدائية -->
                <tr class="main-item">
                    <td>7</td>
                    <td class="col-item text-right" rowspan="5">استخراج رخصة البيع الابتدائية</td>
                    <td class="status-completed col-requirements text-right">حساب الضمان</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">تعاقد مع الاستشاري</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">تعاقد مع المحاسب القانوني</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">دراسة الجدوى</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">دفع رسوم الرخصة</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="status-cell ">تم</td>
                    <td class="col-department text-right"></td>
                    <td class="col-responsible text-right"></td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- اطلاق البيع -->
                <tr class="main-item">
                    <td>8</td>
                    <td class="col-item text-right" rowspan="1">اطلاق البيع</td>
                    <td class="status-inprogress col-requirements text-right">بعد الانتهاء من تجهيز المبيعات</td>
                    <td class="date-cell">2025-09-01</td>
                    <td class="date-cell"></td>
                    <td class="date-cell">-</td>
                    <td>الى نهاية المشروع</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- التأمين -->
                <tr class="status-notstarted main-item">
                    <td>9</td>
                    <td class="col-item text-right" rowspan="1">التأمين</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2025-12-01</td>
                    <td class="date-cell">2026-03-31</td>
                    <td class="date-cell">-</td>
                    <td>120</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">متطلب لرخص البناء</td>
                </tr>

                <!-- رخص البناء -->
                <tr class="main-item">
                    <td>10</td>
                    <td class="col-item text-right" rowspan="2">رخص البناء</td>
                    <td class="status-inprogress col-requirements text-right">اعتماد المخطط العام</td>
                    <td class="date-cell">TBC BY NHC</td>
                    <td class="date-cell">TBC BY NHC</td>
                    <td class="date-cell">-</td>
                    <td>TBC BY NHC</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell">بعد اعتماد المخطط العام</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-completed col-requirements text-right">التأمين</td>
                    <td class="date-cell">2025-12-01</td>
                    <td class="date-cell">2026-04-30</td>
                    <td class="date-cell">-</td>
                    <td>150</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">محمد مسعد</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- استخراج رخصة البيع النهائية -->
                <tr class="main-item">
                    <td>11</td>
                    <td class="status-completed col-item text-right" rowspan="2">استخراج رخصة البيع النهائية</td>
                    <td class="status-inprogress col-requirements text-right">رخص البناء</td>
                    <td class="date-cell">2025-12-01</td>
                    <td class="date-cell">2026-04-30</td>
                    <td class="date-cell">-</td>
                    <td>150</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">تم إصدار رخصة البيع النهائية</td>
                </tr>

                <tr class="sub-item hidden-border">
                    <td></td>
                    <td class="status-inprogress col-requirements text-right">اعتماد المخطط العام</td>
                    <td class="date-cell">TBC BY NHC</td>
                    <td class="date-cell">TBC BY NHC</td>
                    <td class="date-cell">-</td>
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
                    <td class="status-inprogress col-requirements text-right">أنشائي</td>
                    <td class="date-cell">2025-09-17</td>
                    <td class="date-cell">2025-10-17</td>
                    <td class="date-cell">-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-inprogress col-requirements text-right">معماري</td>
                    <td class="date-cell">2025-09-17</td>
                    <td class="date-cell">2025-10-17</td>
                    <td class="date-cell">-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-inprogress col-requirements text-right">ميكانيكا</td>
                    <td class="date-cell">2025-09-17</td>
                    <td class="date-cell">2025-10-17</td>
                    <td class="date-cell">-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-inprogress col-requirements text-right">كهرباء</td>
                    <td class="date-cell">2025-09-17</td>
                    <td class="date-cell">2025-10-17</td>
                    <td class="date-cell">-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <tr class="status-notstarted sub-item hidden-border">
                    <td></td>
                    <td class="status-inprogress col-requirements text-right">مخططات تنسيقية</td>
                    <td class="date-cell">2025-09-17</td>
                    <td class="date-cell">2025-10-17</td>
                    <td class="date-cell">-</td>
                    <td>30</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">صالح</td>
                    <td class="col-notes notes-cell">الاعتماد من استشاري الإشراف</td>
                </tr>

                <!-- استلام الموقع -->
                <tr class="status-notstarted main-item">
                    <td>13</td>
                    <td class="col-item text-right" rowspan="1">استلام الموقع</td>
                    <td class="status-inprogress col-requirements text-right">استلام الموقع</td>
                    <td class="date-cell">2025-11-01</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td>-</td>
                    <td class="col-department text-right">الإدارة العليا</td>
                    <td class="col-responsible text-right">الإدارة العليا</td>
                    <td class="col-notes notes-cell">حسب إفادة رئيس الضاحية</td>
                </tr>

                <!-- استلام IFC DRAWINGS -->
                <tr class="status-notstarted main-item">
                    <td>14</td>
                    <td class="col-item text-right" rowspan="1"></td>
                    <td class="status-inprogress col-requirements text-right">استلام IFC DRAWINGS</td>
                    <td class="date-cell">2025-11-01</td>
                    <td class="date-cell">-</td>
                    <td class="date-cell">-</td>
                    <td>-</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">إدارة التنفيذ</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- تنفيذ البنية الفوقية -->
                <tr class="status-notstarted main-item">
                    <td>15</td>
                    <td class="col-item text-right" rowspan="1">تنفيذ البنية الفوقية (مرتبط بالبيع)</td>
                    <td class="status-inprogress col-requirements text-right">أعمال تنفيذ الفيلات</td>
                    <td class="date-cell">2025-12-01</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">-</td>
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
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2028-03-01</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">-</td>
                    <td>365</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- فرز الصكوك -->
                <tr class="status-notstarted main-item">
                    <td>17</td>
                    <td class="col-item text-right" rowspan="1">فرز الصكوك</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">TBC</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">-</td>
                    <td>-</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">بعد اعتماد المخطط العام</td>
                </tr>

                <!-- توصيل الخدمات -->
                <tr class="status-notstarted main-item">
                    <td>18</td>
                    <td class="col-item text-right" rowspan="1">توصيل الخدمات</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2028-05-01</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">-</td>
                    <td>304</td>
                    <td class="col-department text-right">إدارة التنفيذ</td>
                    <td class="col-responsible text-right">إدارة التنفيذ</td>
                    <td class="col-notes notes-cell">قبل نهاية المشروع بـ 10 شهور</td>
                </tr>

                <!-- تسليم الوحدات -->
                <tr class="status-notstarted main-item">
                    <td>19</td>
                    <td class="col-item text-right" rowspan="1">تسليم الوحدات</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2028-11-01</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">-</td>
                    <td>120</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- الإفراغ -->
                <tr class="status-notstarted main-item">
                    <td>20</td>
                    <td class="col-item text-right" rowspan="1">الإفراغ</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">2030-03-01</td>
                    <td class="date-cell">-</td>
                    <td>365</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- سداد الأرض -->
                <tr class="status-notstarted main-item">
                    <td>21</td>
                    <td class="col-item text-right" rowspan="1">سداد الأرض</td>
                    <td class="status-inprogress col-requirements text-right">شهري</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">2029-07-01</td>
                    <td class="date-cell">-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- سداد البنية التحتية -->
                <tr class="status-notstarted main-item">
                    <td>22</td>
                    <td class="col-item text-right" rowspan="1">سداد البنية التحتية</td>
                    <td class="status-inprogress col-requirements text-right">شهري</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">2029-07-01</td>
                    <td class="date-cell">-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- سداد رسوم الوطنية -->
                <tr class="status-notstarted main-item">
                    <td>23</td>
                    <td class="col-item text-right" rowspan="1">سداد رسوم الوطنية</td>
                    <td class="status-inprogress col-requirements text-right">شهري</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">2029-07-01</td>
                    <td class="date-cell">-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- خدمات ما بعد البيع -->
                <tr class="status-notstarted main-item">
                    <td>24</td>
                    <td class="col-item text-right" rowspan="1">خدمات ما بعد البيع</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2028-11-01</td>
                    <td class="date-cell">2029-11-01</td>
                    <td class="date-cell">-</td>
                    <td>365</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- شهادة إنجاز مشروع مع وافي -->
                <tr class="status-notstarted main-item">
                    <td>25</td>
                    <td class="col-item text-right" rowspan="1">شهادة إنجاز مشروع مع وافي</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">2029-07-01</td>
                    <td class="date-cell">-</td>
                    <td>122</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell"></td>
                </tr>

                <!-- شهادة إتمام بناء -->
                <tr class="status-notstarted main-item">
                    <td>26</td>
                    <td class="col-item text-right" rowspan="1">شهادة إتمام بناء</td>
                    <td class="status-inprogress col-requirements text-right"></td>
                    <td class="date-cell">2028-04-01</td>
                    <td class="date-cell">2029-03-01</td>
                    <td class="date-cell">-</td>
                    <td>334</td>
                    <td class="col-department text-right">إدارة التطوير</td>
                    <td class="col-responsible text-right">إدارة التطوير</td>
                    <td class="col-notes notes-cell">يبدأ مع بداية مرحلة التنفيذ الأخيرة (متطلب لتوصيل الخدمات )</td>
                </tr>

            </tbody>
        </table>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    // دالة لتحميل ملف Excel
    function exportToExcel() {
        const loadingOverlay = showLoading('⏳ جاري إنشاء ملف Excel...');

        try {
            // الحصول على الجدول باستخدام ID الصحيح
            const table = document.getElementById('project-table');

            if (!table) {
                throw new Error('لم يتم العثور على الجدول');
            }

            // تحويل الجدول إلى ورقة عمل
            const ws = XLSX.utils.table_to_sheet(table);

            // إنشاء مصنف جديد
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "خطة المشروع");

            // تنسيق الأعمدة
            if (!ws['!cols']) ws['!cols'] = [];

            // تعيين عرض الأعمدة
            const colWidths = [
                {wch: 5},   // م
                {wch: 25},  // البند
                {wch: 25},  // المتطلبات
                {wch: 12},  // البداية
                {wch: 12},  // النهاية
                {wch: 15},  // النهاية المحدثة
                {wch: 8},   // المدة
                {wch: 15},  // الإدارة
                {wch: 18},  // المسؤول
                {wch: 20}   // ملاحظات
            ];

            ws['!cols'] = colWidths;

            // حفظ الملف
            const timestamp = new Date().toISOString().slice(0, 10);
            XLSX.writeFile(wb, `خطة_مشروع_أزيان_جدة_${timestamp}.xlsx`);

            hideLoading(loadingOverlay);

        } catch (error) {
            console.error('Error generating Excel:', error);
            hideLoading(loadingOverlay);
            alert('حدث خطأ أثناء إنشاء ملف Excel. يرجى المحاولة مرة أخرى.\n' + error.message);
        }
    }

    // دالة لعرض شاشة التحميل
    function showLoading(message = 'جاري التصدير...') {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner"></div>
            <div>${message}</div>
        `;
        document.body.appendChild(overlay);
        return overlay;
    }

    // دالة لإخفاء شاشة التحميل
    function hideLoading(overlay) {
        if (overlay && overlay.parentNode) {
            overlay.parentNode.removeChild(overlay);
        }
    }

    // تحسين الطباعة
    function optimizePrint() {
        const style = document.createElement('style');
        style.innerHTML = `
            @media print {
                @page {
                    size: landscape;
                    margin: 10mm;
                }
                body {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                    background: white !important;
                    padding: 0 !important;
                }
                .controls {
                    display: none !important;
                }
                .container {
                    max-width: 100% !important;
                    margin: 0 !important;
                }
                table {
                    font-size: 11px !important;
                }
                th, td {
                    font-size: 10px !important;
                    line-height: 1.3 !important;
                }
                th {
                    font-size: 11px !important;
                }
                .section-title {
                    font-size: 12px !important;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // استدعاء الدوال عند تحميل الصفحة
    document.addEventListener('DOMContentLoaded', function() {
        optimizePrint();
    });
</script>
@endsection
