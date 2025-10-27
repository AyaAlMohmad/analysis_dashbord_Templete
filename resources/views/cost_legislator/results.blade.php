<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج حساب التكاليف</title>
    <style>
        /* نفس الـ CSS السابق مع إضافات للنتائج */
    </style>
</head>
<body>
    @if(!isset($data) || !isset($results))
    <div style="text-align: center; padding: 50px;">
        <h2>لا توجد بيانات لعرضها</h2>
        <p>يرجى إدخال بيانات المشروع أولاً</p>
        <!-- استخدم المسار المباشر بدلاً من route -->
        <a href="/cost-legislator" class="btn">العودة للنموذج</a>
    </div>
    @else
    <div class="container">

    </div>
    @endif
    <div class="container">
        <header>
            <div class="header-content">
                <div>
                    <h1>نتائج حساب تكاليف المشروع</h1>
                    <p>موازنة مشروع الفرسان بالرياض</p>
                </div>
            </div>
        </header>
        
        <section class="form-section">
            <h2 class="section-title">النتائج</h2>
            
            <!-- عرض النتائج هنا -->
            <div class="results-grid">
                <div class="result-item">
                    <div class="result-label">إجمالي عدد الوحدات</div>
                    <div class="result-value">{{ number_format($results['total_units']) }}</div>
                </div>
                <div class="result-item">
                    <div class="result-label">إجمالي قيمة المشروع</div>
                    <div class="result-value">{{ number_format($results['total_project_value']) }} ر.س</div>
                </div>
                <div class="result-item">
                    <div class="result-label">إجمالي التكاليف</div>
                    <div class="result-value">{{ number_format($results['total_cost']) }} ر.س</div>
                </div>
                <div class="result-item">
                    <div class="result-label">نسبة التكاليف</div>
                    <div class="result-value">{{ number_format($results['cost_percentage'], 2) }}%</div>
                </div>
            </div>
            
            <div class="actions">
                <a href="{{ route('admin.cost.legislator.index') }}" class="btn">العودة للنموذج</a>
            </div>
        </section>
    </div>
</body>
</html>