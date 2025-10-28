<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\ContractsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Contract;

class FinalContractsController extends Controller
{
    /**
     * عرض صفحة العقود النهائية
     */
    public function index()
    {
        // بيانات التاريخ (يمكن جعلها ديناميكية)
        $startDate = '2025-10-01';
        $endDate = '2025-10-27';
        
        // // جلب العقود من قاعدة البيانات
        // $contracts = Contract::whereBetween('contract_date', [$startDate, $endDate])
        //     ->orderBy('contract_date', 'desc')
        //     ->get();
            
        // // إذا لم تكن هناك بيانات، نستخدم البيانات الافتراضية للعرض
        // if ($contracts->isEmpty()) {
        //     $contracts = collect([
        //         (object)[
        //             'client_name' => 'محمد سمسم',
        //             'id_number' => '1004579940',
        //             'mobile' => '0565566970',
        //             'unit_code' => '443',
        //             'unit_price' => '846,505',
        //             'representative' => 'أحمد مشاط',
        //             'contract_date' => '2025-10-26',
        //             'bank' => 'الأول'
        //         ],
        //         (object)[
        //             'client_name' => 'محمد باننا',
        //             'id_number' => '1091151991',
        //             'mobile' => '0560007376',
        //             'unit_code' => '237',
        //             'unit_price' => '1,059,664',
        //             'representative' => 'أحمد مشاط',
        //             'contract_date' => '2025-10-20',
        //             'bank' => 'الأقطاب'
        //         ],
        //         (object)[
        //             'client_name' => 'السا المطرفي',
        //             'id_number' => '1090166131',
        //             'mobile' => '0552044986',
        //             'unit_code' => '429',
        //             'unit_price' => '846,505',
        //             'representative' => 'أحمد مشاط',
        //             'contract_date' => '2025-10-20',
        //             'bank' => 'الأقطاب'
        //         ],
        //         (object)[
        //             'client_name' => 'عبد العوض',
        //             'id_number' => '1058571264',
        //             'mobile' => '0500606025',
        //             'unit_code' => '33',
        //             'unit_price' => '1,323,607',
        //             'representative' => 'روا عبدالله',
        //             'contract_date' => '2025-10-09',
        //             'bank' => 'نقدا - دفعات'
        //         ],
        //         (object)[
        //             'client_name' => 'محمد محبوب بن',
        //             'id_number' => '1014864985',
        //             'mobile' => '0505696017',
        //             'unit_code' => '448',
        //             'unit_price' => '846,505',
        //             'representative' => 'أحمد مشاط',
        //             'contract_date' => '2025-10-06',
        //             'bank' => 'الأول'
        //         ]
        //     ]);
        // }
        
        return view('final_contracts.index', compact( 'startDate', 'endDate'));
    }
    
    /**
     * البحث في العقود
     */
    public function search(Request $request)
    {
        $searchTerm = $request->get('search');
        $filter = $request->get('filter', 'all');
        
        $query = Contract::query();
        
        // تطبيق البحث
        if ($searchTerm) {
            $query->where(function($q) use ($searchTerm) {
                $q->where('client_name', 'like', "%{$searchTerm}%")
                  ->orWhere('id_number', 'like', "%{$searchTerm}%")
                  ->orWhere('mobile', 'like', "%{$searchTerm}%")
                  ->orWhere('representative', 'like', "%{$searchTerm}%")
                  ->orWhere('bank', 'like', "%{$searchTerm}%")
                  ->orWhere('unit_code', 'like', "%{$searchTerm}%");
            });
        }
        
        // تطبيق التصفية
        if ($filter !== 'all') {
            $query->where(function($q) use ($filter) {
                $q->where('bank', $filter)
                  ->orWhere('representative', $filter);
            });
        }
        
        $contracts = $query->orderBy('contract_date', 'desc')->get();
        
        if ($request->ajax()) {
            return response()->json([
                'contracts' => $contracts,
                'total' => $contracts->count()
            ]);
        }
        
        return view('final-contracts.index', compact('contracts'));
    }
    
    /**
     * تصدير العقود إلى Excel
     */
    public function exportExcel(Request $request)
    {
        $searchTerm = $request->get('search');
        $filter = $request->get('filter', 'all');
        
        return Excel::download(new ContractsExport($searchTerm, $filter), 'العقود_النهائية.xlsx');
    }
    
    /**
     * إنشاء عقد جديد
     */
    public function create()
    {
        return view('final-contracts.create');
    }
    
    /**
     * حفظ العقد الجديد
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:20',
            'mobile' => 'required|string|max:15',
            'unit_code' => 'required|string|max:10',
            'unit_price' => 'required|numeric',
            'representative' => 'required|string|max:255',
            'contract_date' => 'required|date',
            'bank' => 'required|string|max:255'
        ]);
        
        // تحويل سعر الوحدة إلى التنسيق المطلوب
        $validated['unit_price'] = number_format($validated['unit_price'], 0);
        
        Contract::create($validated);
        
        return redirect()->route('final-contracts.index')
            ->with('success', 'تم إضافة العقد بنجاح');
    }
    
    /**
     * عرض تفاصيل عقد محدد
     */
    public function show($id)
    {
        $contract = Contract::findOrFail($id);
        
        return view('final-contracts.show', compact('contract'));
    }
    
    /**
     * تحرير عقد محدد
     */
    public function edit($id)
    {
        $contract = Contract::findOrFail($id);
        
        return view('final-contracts.edit', compact('contract'));
    }
    
    /**
     * تحديث العقد
     */
    public function update(Request $request, $id)
    {
        $contract = Contract::findOrFail($id);
        
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'id_number' => 'required|string|max:20',
            'mobile' => 'required|string|max:15',
            'unit_code' => 'required|string|max:10',
            'unit_price' => 'required|numeric',
            'representative' => 'required|string|max:255',
            'contract_date' => 'required|date',
            'bank' => 'required|string|max:255'
        ]);
        
        // تحويل سعر الوحدة إلى التنسيق المطلوب
        $validated['unit_price'] = number_format($validated['unit_price'], 0);
        
        $contract->update($validated);
        
        return redirect()->route('final-contracts.index')
            ->with('success', 'تم تحديث العقد بنجاح');
    }
    
    /**
     * حذف عقد
     */
    public function destroy($id)
    {
        $contract = Contract::findOrFail($id);
        $contract->delete();
        
        return redirect()->route('final-contracts.index')
            ->with('success', 'تم حذف العقد بنجاح');
    }
    
    /**
     * إحصائيات العقود
     */
    public function statistics()
    {
        $totalContracts = Contract::count();
        $totalValue = Contract::sum(DB::raw('REPLACE(unit_price, ",", "")'));
        $contractsByBank = Contract::groupBy('bank')
            ->select('bank', DB::raw('count(*) as total'))
            ->get();
        $contractsByRepresentative = Contract::groupBy('representative')
            ->select('representative', DB::raw('count(*) as total'))
            ->get();
            
        return view('final-contracts.statistics', compact(
            'totalContracts',
            'totalValue',
            'contractsByBank',
            'contractsByRepresentative'
        ));
    }
}