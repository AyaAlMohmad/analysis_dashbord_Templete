<?php
// app/Http/Controllers/ProjectPlanController.php

namespace App\Http\Controllers;

use App\Models\ProjectPlan;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectPlanController extends Controller
{
      public function index($site)
    {
        $site = $this->validateSite($site);

        // جلب الأقسام الرئيسية حسب الموقع
        $mainSections = ProjectPlan::where('item_type', 'section')
            ->where('site', $site)
            ->orderBy('sort_order')
            ->get();

        // جلب جميع البنود حسب الموقع
        $allPlans = ProjectPlan::where('site', $site)
            ->with(['children' => function ($query) {
                $query->orderBy('sort_order');
            }])
            ->get();

        $siteName = $this->getSiteName($site);

        return view('project-plans.index', compact('mainSections', 'allPlans', 'siteName', 'site'));
    }

    public function create($site)
    {
        $site = $site;

        // جلب البنود الرئيسية لنفس الموقع فقط
        $mainItems = ProjectPlan::where('site', $site)
            ->where(function ($query) {
                $query->where('item_type', 'section')
                    ->orWhere(function ($q) {
                        $q->where('item_type', 'main')
                            ->whereNull('parent_id');
                    });
            })
            ->orderBy('sort_order')
            ->get();

        // جلب الأقسام لنفس الموقع فقط
        $sections = ProjectPlan::where('site', $site)
            ->where('item_type', 'section')
            ->get();

        $users = User::all();
        $siteName = $this->getSiteName($site);

        return view('project-plans.create', compact('mainItems', 'sections', 'users', 'site', 'siteName'));
    }

    public function store(Request $request, $site)
    {
        $site = $site;
// dd($site);
        $validated = $request->validate([
            'item_type' => 'required|in:section,main,sub',
            'item_name' => 'required_if:item_type,section,main',
            'item_number' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'updated_end_date' => 'nullable|date',
            'duration' => 'nullable|string',
            'department' => 'nullable|string',
            'responsible' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:notstarted,inprogress,completed',
            'parent_id' => 'nullable|exists:project_plans,id',
            'parent_section' => 'nullable|string',
            'sort_order' => 'sometimes|integer',
            'site' => 'nullable|string',
        ]);

        // إضافة الموقع إلى البيانات المصدقة
        $validated['site'] = $site;

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
            $maxSortOrder = ProjectPlan::where('site', $site)
                ->where('parent_section', $validated['parent_section'])
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
            $validated['parent_section'] =$request->item_name;
        }
// dd($validated);
        // تحويل status إلى status_class
        $validated['status_class'] = 'status-' . $validated['status'];
        unset($validated['status']);

        ProjectPlan::create($validated);

        return redirect()->route('admin.project_plan.index', ['site' => $site])
            ->with('success', 'تم إضافة البند بنجاح');
    }

    public function edit($site, $id)
    {
        $site = $site;

        // التأكد من أن البند ينتمي للموقع المطلوب
        $projectPlan = ProjectPlan::where('site', $site)->findOrFail($id);

        // جلب البنود الرئيسية لنفس الموقع فقط
        $mainItems = ProjectPlan::where('site', $site)
            ->where(function ($query) {
                $query->where('item_type', 'section')
                    ->orWhere(function ($q) {
                        $q->where('item_type', 'main')
                            ->whereNull('parent_id');
                    });
            })
            ->orderBy('sort_order')
            ->get();

        // جلب الأقسام لنفس الموقع فقط
        $sections = ProjectPlan::where('site', $site)
            ->where('item_type', 'section')
            ->pluck('parent_section')
            ->unique()
            ->values()
            ->toArray();

        $users = User::all();
        $siteName = $this->getSiteName($site);

        return view('project-plans.edit', compact('projectPlan', 'mainItems', 'sections', 'users', 'site', 'siteName'));
    }

    public function update(Request $request, $site, $id)
    {
        $site = $this->validateSite($site);

        // التأكد من أن البند ينتمي للموقع المطلوب
        $projectPlan = ProjectPlan::where('site', $site)->findOrFail($id);

        $validated = $request->validate([
            'item_type' => 'required|in:section,main,sub',
            'item_name' => 'required_if:item_type,section,main',
            'item_number' => 'nullable|integer',
            'requirements' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'updated_end_date' => 'nullable|date',
            'duration' => 'nullable|string',
            'department' => 'nullable|string',
            'responsible' => 'nullable|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:notstarted,inprogress,completed',
            'parent_id' => 'nullable|exists:project_plans,id',
            'parent_section' => 'nullable|string',
            'sort_order' => 'required|integer',
        ]);

        // التأكد من أن الموقع يبقى كما هو
        $validated['site'] = $site;

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

        return redirect()->route('admin.project_plan.index', ['site' => $site])
            ->with('success', 'تم تحديث البند بنجاح');
    }

    public function destroy($site, $id)
    {
        $site = $this->validateSite($site);

        // التأكد من أن البند ينتمي للموقع المطلوب
        $projectPlan = ProjectPlan::where('site', $site)->findOrFail($id);

        $projectPlan->delete();

        return redirect()->route('admin.project_plan.index', ['site' => $site])
            ->with('success', 'تم حذف البند بنجاح');
    }

    public function updateStatus(Request $request, $site, $id)
    {
        $site = $this->validateSite($site);

        try {
            // التأكد من أن البند ينتمي للموقع المطلوب
            $projectPlan = ProjectPlan::where('site', $site)->findOrFail($id);

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

    public function getParentsBySection(Request $request, $site, $section)
    {
        $site = $this->validateSite($site);

        $parents = ProjectPlan::where('site', $site)
            ->where('parent_section', $section)
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

    /**
     * التحقق من صحة الموقع
     */
    private function validateSite($site)
    {
        $allowedSites = ['jeddah', 'dhahran', 'bashaer', 'alfursan'];
        return in_array($site, $allowedSites) ? $site : 'jeddah';
    }

    /**
     * الحصول على اسم الموقع بالعربية
     */
    private function getSiteName($site)
    {
        $siteNames = [
            'jeddah' => 'جدة',
            'dhahran' => 'الظهران',
            'bashaer' => 'البشائر',
            'alfursan' => 'الفورسان',

        ];

        return $siteNames[$site] ?? 'جدة';
    }
}
