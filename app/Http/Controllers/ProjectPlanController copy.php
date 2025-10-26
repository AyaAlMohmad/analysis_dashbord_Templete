<?php
// app/Http/Controllers/ProjectPlanController.php

namespace App\Http\Controllers;

use App\Models\ProjectPlan; // تأكد من هذا الاستيراد
use App\Models\User;
use Illuminate\Http\Request;

class ProjectPlanController extends Controller
{

    public function create()
    {
        // جلب جميع الأباء المحتملين (أقسام وبنود رئيسية)
        $mainItems = ProjectPlan::where(function ($query) {
            $query->where('item_type', 'section')
                ->orWhere(function ($q) {
                    $q->where('item_type', 'main')
                        ->whereNull('parent_id');
                });
        })->orderBy('sort_order')->get();

        // جلب جميع الأقسام الفريدة من قاعدة البيانات
        $sections = ProjectPlan::where('item_type', 'section')->get();

        $users = User::all();

        return view('project-plans.create', compact('mainItems', 'sections', 'users'));
    }
    public function index()
    {
        // جلب الأقسام الرئيسية فقط
        $mainSections = ProjectPlan::where('item_type', 'section')
            ->whereIn('parent_section', ['ما قبل الترسية', 'الترسية وتوقيع الاتفاقية', 'التنفيذ', 'التسليم'])
            ->orderByRaw("
                CASE
                    WHEN parent_section = 'ما قبل الترسية' THEN 1
                    WHEN parent_section = 'الترسية وتوقيع الاتفاقية' THEN 2
                    WHEN parent_section = 'التنفيذ' THEN 3
                    WHEN parent_section = 'التسليم' THEN 4
                    ELSE 5
                END
            ")
            ->orderBy('sort_order')
            ->get();

        // جلب جميع البنود مع علاقاتها
        $allPlans = ProjectPlan::with(['children' => function ($query) {
            $query->orderBy('sort_order');
        }])->get();

        $siteName = "أزيان جدة";

        // إرجاع جميع المتغيرات المطلوبة
        return view('project-plans.index', compact('mainSections', 'allPlans', 'siteName'));
    }
    // app/Http/Controllers/ProjectPlanController.php

// أضف هذه الدالة في ProjectPlanController
public function updateStatus(Request $request, $id)
{
    try {
        $projectPlan = ProjectPlan::findOrFail($id);

        // التحقق من الصلاحيات
        if (!auth()->user()->hasPermission('edit_projects') &&
            !auth()->user()->hasPermission('manage_project_actions') &&
            auth()->user()->name != $projectPlan->responsible) {

            return response()->json([
                'success' => false,
                'message' => 'ليس لديك صلاحية لتعديل هذا البند'
            ], 403);
        }

        $status = $request->input('status');
        $updateData = [
            'status_class' => 'status-' . $status,
            // 'updated_end_date' => now()
        ];

        // إذا كانت الحالة "تم"، قم بتحديث تاريخ النهاية الفعلي
        if ($status === 'completed') {
            $updateData['updated_end_date'] = now();
        }

        // تحديث الحالة
        $projectPlan->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحالة بنجاح'
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'حدث خطأ: ' . $e->getMessage()
        ], 500);
    }
}
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_type' => 'required|in:section,main,sub',
            'item_name' => 'required_if:item_type,section,main',
            'item_number' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'updated_end_date' => 'nullable|date', // تغيير إلى string
            'duration' => 'nullable|string',
            'department' => 'nullable|string',
            'responsible' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:notstarted,inprogress,completed',
            'parent_id' => 'nullable|exists:project_plans,id',
            'parent_section' => 'required|string',
            'sort_order' => 'sometimes|integer',
        ]);

        // معالجة التواريخ الفارغة
        if (empty($validated['start_date'])) {
            $validated['start_date'] = null;
        }
        if (empty($validated['end_date'])) {
            $validated['end_date'] = null;
        }
        if (empty($validated['updated_end_date'])) {
            $validated['updated_end_date'] = null;
        }

        // حساب قيمة sort_order تلقائيًا إذا لم يتم تقديمها
        if (!isset($validated['sort_order']) || empty($validated['sort_order'])) {
            $maxSortOrder = ProjectPlan::where('parent_section', $validated['parent_section'])
                ->max('sort_order') ?? 0;
            $validated['sort_order'] = $maxSortOrder + 1;
        }

        // إذا كان البند فرعي، تأكد من وجود parent_id
        if ($validated['item_type'] === 'sub' && empty($validated['parent_id'])) {
            return back()->withErrors(['parent_id' => 'البند الفرعي يجب أن يكون له بند أب'])->withInput();
        }

        // إذا كان البند قسم، لا يمكن أن يكون له أب
        if ($validated['item_type'] === 'section' && !empty($validated['parent_id'])) {
            $validated['parent_id'] = null;
        }

        // تحويل status إلى status_class
        $validated['status_class'] = 'status-' . $validated['status'];
        unset($validated['status']);

        ProjectPlan::create($validated);

        return redirect()->route('admin.project_plan.index')
            ->with('success', 'تم إضافة البند بنجاح');
    }
    public function getParentsBySection($section)
    {
        $parents = ProjectPlan::where('parent_section', $section)
            ->where(function ($query) {
                $query->where('item_type', 'section')
                    ->orWhere(function ($q) {
                        $q->where('item_type', 'main')
                            ->whereNull('parent_id');
                    });
            })
            ->orderBy('sort_order')
            ->get();

        return response()->json($parents);
    }

    public function edit($id)
    {
        $projectPlan = ProjectPlan::findOrFail($id);

        $mainItems = ProjectPlan::where(function ($query) {
            $query->where('item_type', 'section')
                ->orWhere(function ($q) {
                    $q->where('item_type', 'main')
                        ->whereNull('parent_id');
                });
        })->orderBy('sort_order')->get();

        $sections = ProjectPlan::where('item_type', 'section')
            ->pluck('parent_section')
            ->unique()
            ->values()
            ->toArray();

        $users = User::all();

        return view('project-plans.edit', compact('projectPlan', 'mainItems', 'sections', 'users'));
    }

    public function update(Request $request, ProjectPlan $projectPlan)
    {
        $validated = $request->validate([
            'item_type' => 'required|in:section,main,sub',
            'item_name' => 'required_if:item_type,section,main',
            'item_number' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'updated_end_date' => 'nullable|date', // تغيير من updated_end_date إلى actual_end_date
            'duration' => 'nullable|string',
            'department' => 'nullable|string',
            'responsible' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:notstarted,inprogress,completed',
            'parent_id' => 'nullable|exists:project_plans,id',
            'parent_section' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        // معالجة التواريخ الفارغة
        if (empty($validated['start_date'])) {
            $validated['start_date'] = null;
        }
        if (empty($validated['end_date'])) {
            $validated['end_date'] = null;
        }
        if (empty($validated['updated_end_date'])) {
            $validated['updated_end_date'] = null;
        }


        // إذا كان البند قسم رئيسي، استخدم item_name ك parent_section
        if ($validated['item_type'] === 'section') {
            $validated['parent_section'] = $validated['item_name'];
        }

        // إذا كان البند فرعي، تأكد من وجود parent_id
        if ($validated['item_type'] === 'sub' && empty($validated['parent_id'])) {
            return back()->withErrors(['parent_id' => 'البند الفرعي يجب أن يكون له بند أب'])->withInput();
        }

        // إذا كان البند قسم، لا يمكن أن يكون له أب
        if ($validated['item_type'] === 'section' && !empty($validated['parent_id'])) {
            $validated['parent_id'] = null;
        }

        // تحويل status إلى status_class
        $validated['status_class'] = 'status-' . $validated['status'];
        unset($validated['status']);

        $projectPlan->update($validated);

        return redirect()->route('admin.project_plan.index')
            ->with('success', 'تم تحديث البند بنجاح');
    }

    public function destroy(ProjectPlan $projectPlan)
    {
        $projectPlan->delete();

        return redirect()->route('admin.project_plan.index')
            ->with('success', 'تم حذف البند بنجاح');
    }
}
