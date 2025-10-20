<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectPlanController extends Controller
{
    public function index()
    {
        $projectData = [
            // ما قبل الترسية
            ['id' => '', 'item' => 'ما قبل الترسية', 'requirements' => '', 'start' => '', 'end' => '', 'updatedEnd' => '', 'duration' => '', 'department' => '', 'responsible' => '', 'notes' => '', 'status' => ''],
            ['id' => 1, 'item' => 'استقبال الدعوات', 'requirements' => '', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => 'حسب مدة الطرح وتقديم العروض', 'department' => '-', 'responsible' => '-', 'notes' => '', 'status' => 'not-started'],
            
            ['id' => 2, 'item' => 'الدراسة الفنية', 'requirements' => 'معاينة الموقع', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التنفيذ', 'responsible' => 'وليد عطية', 'notes' => '', 'status' => 'not-started'],
            ['id' => '', 'item' => '', 'requirements' => 'تقرير فنى بحالة الخدمات بالموقع - الرفع المساحي', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التنفيذ', 'responsible' => 'وليد عطية', 'notes' => '', 'status' => 'not-started'],
            ['id' => '', 'item' => '', 'requirements' => 'تقرير تربة', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التنفيذ', 'responsible' => 'وليد عطية', 'notes' => '', 'status' => 'not-started'],
            
            ['id' => 3, 'item' => 'الدراسة المالية', 'requirements' => 'بنية فوقية', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التنفيذ', 'responsible' => 'وليد عطية', 'notes' => '', 'status' => 'not-started'],
            ['id' => '', 'item' => '', 'requirements' => 'بنية تحتية', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التنفيذ', 'responsible' => 'وليد عطية', 'notes' => '', 'status' => 'not-started'],
            ['id' => '', 'item' => '', 'requirements' => 'دراسة السوق', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التطوير', 'responsible' => 'محمد مسعد', 'notes' => '', 'status' => 'not-started'],
            
            ['id' => 4, 'item' => 'تقديم العرض الفني والمالي', 'requirements' => '', 'start' => '-', 'end' => '-', 'updatedEnd' => '-', 'duration' => '', 'department' => 'إدارة التطوير', 'responsible' => 'محمد مسعد', 'notes' => '', 'status' => 'not-started'],
            
            // الترسية وتوقيع الاتفاقية
            ['id' => '', 'item' => 'الترسية وتوقيع الاتفاقية', 'requirements' => '', 'start' => '', 'end' => '', 'updatedEnd' => '', 'duration' => '', 'department' => '', 'responsible' => '', 'notes' => '', 'status' => ''],
            
            // باقي البيانات بنفس الطريقة...
        ];

        return view('project_plan.index', compact('projectData'));
    }
}