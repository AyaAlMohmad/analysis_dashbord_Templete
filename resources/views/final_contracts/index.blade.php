@extends('layouts.app')

@section('content')
<style>
    .contracts-container {
        padding: 20px;
        background: #f8f9fa;
        min-height: 100vh;
        direction: rtl;
    }
    
    .contracts-header {
        background: white;
        padding: 25px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        text-align: center;
    }
    
    .contracts-title {
        font-size: 24px;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 10px;
    }
    
    .date-range {
        color: #7f8c8d;
        font-size: 16px;
    }
    
    .contracts-table-container {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .contracts-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        direction: rtl;
    }
    
    .contracts-table th {
        background: #34495e;
        color: white;
        padding: 12px 8px;
        text-align: right;
        font-weight: 600;
        border: none;
    }
    
    .contracts-table td {
        padding: 10px 8px;
        border-bottom: 1px solid #ecf0f1;
        text-align: right;
    }
    
    .contracts-table tbody tr:nth-child(even) {
        background-color: #fafafa;
    }
    
    .contracts-table tbody tr:hover {
        background-color: #f8f9fa;
    }
    
    .contracts-summary {
        background: #27ae60;
        color: white;
        padding: 15px;
        text-align: center;
        direction: rtl;
    }
    
    .total-contracts {
        font-size: 16px;
        font-weight: bold;
    }
</style>

<div class="contracts-container">
    <div class="contracts-header">
        <h1 class="contracts-title">العقود النهائية الموقعة</h1>
        <div class="date-range">
            من: <strong>01/10/2025</strong> إلى: <strong>27/10/2025</strong>
        </div>
    </div>

    <div class="contracts-table-container">
        <table class="contracts-table">
            <thead>
                <tr>
                    <th>اسم العميل</th>
                    <th>رقم الهوية</th>
                    <th>رقم الجوال</th>
                    <th>كود الوحدة</th>
                    <th>سعر الوحدة</th>
                    <th>اسم المندوب</th>
                    <th>تاريخ التعاقد</th>
                    <th>البنك الممول</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>محمد سمسم</td>
                    <td>1004579940</td>
                    <td>0565566970</td>
                    <td>443</td>
                    <td>846,505</td>
                    <td>أحمد مشاط</td>
                    <td>26/10/2025</td>
                    <td>الأول</td>
                </tr>
                <tr>
                    <td>محمد باننا</td>
                    <td>1091151991</td>
                    <td>0560007376</td>
                    <td>237</td>
                    <td>1,059,664</td>
                    <td>أحمد مشاط</td>
                    <td>20/10/2025</td>
                    <td>الأقطاب</td>
                </tr>
                <tr>
                    <td>السا المطرفي</td>
                    <td>1090166131</td>
                    <td>0552044986</td>
                    <td>429</td>
                    <td>846,505</td>
                    <td>أحمد مشاط</td>
                    <td>20/10/2025</td>
                    <td>الأقطاب</td>
                </tr>
                <tr>
                    <td>عبد العوض</td>
                    <td>1058571264</td>
                    <td>0500606025</td>
                    <td>33</td>
                    <td>1,323,607</td>
                    <td>روا عبدالله</td>
                    <td>09/10/2025</td>
                    <td>نقدا - دفعات</td>
                </tr>
                <tr>
                    <td>محمد محبوب بن</td>
                    <td>1014864985</td>
                    <td>0505696017</td>
                    <td>448</td>
                    <td>846,505</td>
                    <td>أحمد مشاط</td>
                    <td>06/10/2025</td>
                    <td>الأول</td>
                </tr>
            </tbody>
        </table>
        
        <div class="contracts-summary">
            <span class="total-contracts">إجمالي: 5</span>
        </div>
    </div>
</div>
@endsection