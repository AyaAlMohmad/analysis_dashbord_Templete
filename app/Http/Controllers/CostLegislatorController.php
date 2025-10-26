<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CostLegislatorController extends Controller
{
    /**
     * عرض نموذج تكلفة المشروع
     */
    public function index()
    {
        return view('cost_legislator.index');
    }
    
    /**
     * معالجة بيانات النموذج وحساب التكاليف
     */
   public function calculate(Request $request)
{
    // التحقق من الصلاحيات
    if (!auth()->check() || !auth()->user()->is_admin) {
        abort(403, 'Unauthorized');
    }
    
    // التحقق من البيانات المدخلة
    $validated = $request->validate([
        'villa_count' => 'required|integer|min:0',
        'townhouse_count' => 'required|integer|min:0',
        'apartment_count' => 'required|integer|min:0',
        'villa_price' => 'required|numeric|min:0',
        'townhouse_price' => 'required|numeric|min:0',
        'apartment_price' => 'required|numeric|min:0',
        'sales_months' => 'required|integer|min:1',
        'cost_ratio' => 'required|numeric|min:0'
    ]);
    
    // إجراء الحسابات
    $results = $this->performCalculations($validated);
    
    // حفظ البيانات في session
    session([
        'cost_calculation_data' => $validated,
        'cost_calculation_results' => $results
    ]);
    
    // إرجاع النتائج
    return view('cost_legislator.results', [
        'data' => $validated,
        'results' => $results
    ]);
}
    
    
    /**
     * تنفيذ الحسابات
     */
    private function performCalculations($data)
    {
        // حساب إجمالي الوحدات
        $totalUnits = $data['villa_count'] + $data['townhouse_count'] + $data['apartment_count'];
        
        // حساب إجمالي قيمة المشروع
        $totalProjectValue = ($data['villa_count'] * $data['villa_price']) + 
                            ($data['townhouse_count'] * $data['townhouse_price']) + 
                            ($data['apartment_count'] * $data['apartment_price']);

        // 1- تكلفة المواد والتجهيزات التسويقية الثابتة
        $fixedMarketingItems = [
            ['name' => 'هوية', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 5000],
            ['name' => 'تأسيس موقع الكتروني المشروع', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 15000],
            ['name' => 'الافتتاح', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 50000],
            ['name' => 'مطبوعات', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 20000],
            ['name' => 'النظام', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 30000],
            ['name' => '3d movie', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 80000],
            ['name' => 'Interior design', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 100000],
            ['name' => 'أخرى', 'payment_method' => 'تدفع على كذا دفعة حسب الحاجة', 'notes' => 'مثل لوحات إعلانية بالشوارع وغيرها', 'cost' => 50000],
            ['name' => 'أجهزة', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 40000],
            ['name' => 'مجسمات عرض', 'payment_method' => 'مرة واحدة في بداية المشروع', 'notes' => '', 'cost' => 75000],
        ];
        
        $fixedMarketingCost = collect($fixedMarketingItems)->sum('cost');

        // 2- تكلفة تسويق المشروع الدورية
        $recurringMarketingItems = [
            ['name' => 'الحملات الاعلانية', 'payment_method' => 'مبلغ شهري', 'monthly_amount' => 10000, 'notes' => '', 'total_cost' => 10000 * $data['sales_months']],
            ['name' => 'اشتراكات وانظمة اخرى', 'payment_method' => 'مبلغ شهري', 'monthly_amount' => 3000, 'notes' => '', 'total_cost' => 3000 * $data['sales_months']],
            ['name' => 'انتاج فني', 'payment_method' => 'مبلغ شهري', 'monthly_amount' => 2000, 'notes' => '', 'total_cost' => 2000 * $data['sales_months']],
            ['name' => 'عروض استرداد نقدي للعملاء', 'payment_method' => 'يدفع على فترات طوال مدة المشروع (ليس مبلغ ثابت)', 'monthly_amount' => 1500, 'notes' => '', 'total_cost' => 1500 * $data['sales_months']],
            ['name' => 'معارض وفعاليات', 'payment_method' => 'يدفع على فترات طوال مدة المشروع (ليس مبلغ ثابت)', 'monthly_amount' => 1500, 'notes' => '', 'total_cost' => 1500 * $data['sales_months']],
            ['name' => 'مؤثرين', 'payment_method' => 'يدفع على فترات طوال مدة المشروع (ليس مبلغ ثابت)', 'monthly_amount' => 900, 'notes' => '', 'total_cost' => 900 * $data['sales_months']],
        ];
        
        $recurringMarketingMonthly = collect($recurringMarketingItems)->sum('monthly_amount');
        $recurringMarketingCost = $recurringMarketingMonthly * $data['sales_months'];

        // 3- التكلفة الشهرية لموظفين المبيعات والاستشارات
        $salesStaffItems = [
            ['position' => 'مشرف مبيعات', 'payment_method' => 'مبلغ شهري', 'salary' => 8000, 'employee_count' => 1, 'monthly_cost' => 8000, 'notes' => '', 'total_cost' => 8000 * $data['sales_months']],
            ['position' => 'ممثل مبيعات', 'payment_method' => 'مبلغ شهري', 'salary' => 6000, 'employee_count' => 4, 'monthly_cost' => 24000, 'notes' => '', 'total_cost' => 24000 * $data['sales_months']],
            ['position' => 'متابع عقود', 'payment_method' => 'مبلغ شهري', 'salary' => 5000, 'employee_count' => 1, 'monthly_cost' => 5000, 'notes' => '', 'total_cost' => 5000 * $data['sales_months']],
            ['position' => 'مشرف مبيعات هاتفية', 'payment_method' => 'مبلغ شهري', 'salary' => 7000, 'employee_count' => 1, 'monthly_cost' => 7000, 'notes' => '', 'total_cost' => 7000 * $data['sales_months']],
            ['position' => 'كول سنتر', 'payment_method' => 'مبلغ شهري', 'salary' => 4500, 'employee_count' => 4, 'monthly_cost' => 18000, 'notes' => '', 'total_cost' => 18000 * $data['sales_months']],
        ];
        
        $salesStaffMonthly = collect($salesStaffItems)->sum('monthly_cost');
        $salesStaffTotal = $salesStaffMonthly * $data['sales_months'];

        // تكلفة شركات الاستشارات
        $consultancyItems = [
            ['company' => 'شركة الكيان المتحدة', 'payment_method' => 'مبلغ شهري', 'notes' => '', 'monthly_amount' => 15000, 'total_cost' => 15000 * $data['sales_months']],
            ['company' => 'شركة دار الارجوان (مسار)', 'payment_method' => 'مبلغ شهري', 'notes' => '', 'monthly_amount' => 10000, 'total_cost' => 10000 * $data['sales_months']],
        ];
        
        $consultancyMonthly = collect($consultancyItems)->sum('monthly_amount');
        $consultancyTotal = $consultancyMonthly * $data['sales_months'];
        $salesStaffCost = $salesStaffTotal + $consultancyTotal;

        // 4- المصاريف العمومية الدورية
        $operationalItems = [
            ['name' => 'مصاريف تشغيلية لوجستية وتشغيل مراكز ومكاتب بيع', 'payment_method' => 'مبلغ شهري تقريبي', 'notes' => '', 'monthly_amount' => 1000, 'total_cost' => 1000 * $data['sales_months']],
        ];
        
        $operationalMonthly = collect($operationalItems)->sum('monthly_amount');
        $operationalCost = $operationalMonthly * $data['sales_months'];

        // توزيع العمولات
        $commissionItems = [
            ['name' => 'استشاري المبيعات', 'payment_method' => 'الوحده المباعه', 'notes' => '', 'unit_amount' => 2000, 'total_cost' => 2000 * $totalUnits],
            ['name' => 'مركز الاتصال', 'payment_method' => 'الوحده المباعه', 'notes' => '', 'unit_amount' => 500, 'total_cost' => 500 * $totalUnits],
            ['name' => 'عمولة مدير ومشرف المبيعات', 'payment_method' => 'الوحده المباعه', 'notes' => '0.2', 'unit_amount' => 400, 'total_cost' => 400 * $totalUnits],
            ['name' => 'عمولة قسم التسويق', 'payment_method' => 'الوحده المباعه', 'notes' => '', 'unit_amount' => 1000, 'total_cost' => 1000 * $totalUnits],
            ['name' => 'عمولة شركة الكيان المتحدة', 'payment_method' => 'الوحده المباعه', 'notes' => '', 'unit_amount' => 1500, 'total_cost' => 1500 * $totalUnits],
            ['name' => 'عمولة شركة دار الارجوان (مسار)', 'payment_method' => 'الوحده المباعه', 'notes' => '', 'unit_amount' => 1000, 'total_cost' => 1000 * $totalUnits],
        ];
        
        $commissionUnitTotal = collect($commissionItems)->sum('unit_amount');
        $commissionCost = collect($commissionItems)->sum('total_cost');

        // إجمالي التكاليف
        $totalCostBeforeCommission = $fixedMarketingCost + $recurringMarketingCost + $salesStaffCost + $operationalCost;
        $totalCost = $totalCostBeforeCommission + $commissionCost;

        return [
            'total_units' => $totalUnits,
            'total_project_value' => $totalProjectValue,
            
            // التكاليف الأساسية
            'fixed_marketing_cost' => $fixedMarketingCost,
            'recurring_marketing_cost' => $recurringMarketingCost,
            'sales_staff_cost' => $salesStaffCost,
            'operational_cost' => $operationalCost,
            'total_cost_before_commission' => $totalCostBeforeCommission,
            'commission_cost' => $commissionCost,
            'total_cost' => $totalCost,
            
            // البيانات التفصيلية
            'fixed_marketing_items' => $fixedMarketingItems,
            'recurring_marketing_items' => $recurringMarketingItems,
            'recurring_marketing_monthly' => $recurringMarketingMonthly,
            'sales_staff_items' => $salesStaffItems,
            'sales_staff_monthly' => $salesStaffMonthly,
            'sales_staff_total' => $salesStaffTotal,
            'consultancy_items' => $consultancyItems,
            'consultancy_monthly' => $consultancyMonthly,
            'consultancy_total' => $consultancyTotal,
            'operational_items' => $operationalItems,
            'operational_monthly' => $operationalMonthly,
            'commission_items' => $commissionItems,
            'commission_unit_total' => $commissionUnitTotal,
        ];
    }
    
    /**
     * تصدير البيانات إلى Excel
     */
    public function export(Request $request)
    {
        // التحقق من الصلاحيات
        if (!auth()->check() || !auth()->user()->is_admin) {
            abort(403, 'Unauthorized');
        }
        
        $data = $request->all();
        return $this->generateExcel($data);
    }
    
    /**
     * إنشاء ملف Excel
     */
    private function generateExcel($data)
    {
        $fileName = 'project_cost_' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            fputcsv($file, ['نوع الوحدة', 'العدد', 'متوسط السعر', 'الإجمالي']);
            
            if (isset($data['villa_count'])) {
                fputcsv($file, [
                    'فيلا',
                    $data['villa_count'],
                    number_format($data['villa_price']),
                    number_format($data['villa_count'] * $data['villa_price'])
                ]);
            }
            
            if (isset($data['townhouse_count'])) {
                fputcsv($file, [
                    'تاون هاوس',
                    $data['townhouse_count'],
                    number_format($data['townhouse_price']),
                    number_format($data['townhouse_count'] * $data['townhouse_price'])
                ]);
            }
            
            if (isset($data['apartment_count'])) {
                fputcsv($file, [
                    'شقة',
                    $data['apartment_count'],
                    number_format($data['apartment_price']),
                    number_format($data['apartment_count'] * $data['apartment_price'])
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function results()
{
    // إذا لم تكن هناك بيانات، ارجع إلى الصفحة الرئيسية
    if (!session()->has('cost_calculation_data')) {
        return redirect()->route('admin.cost.legislator.index');
    }

    return view('cost_legislator.results', [
        'data' => session('cost_calculation_data'),
        'results' => session('cost_calculation_results')
    ]);
}
}