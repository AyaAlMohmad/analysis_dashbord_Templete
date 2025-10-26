<?php
// database/seeders/RolePermissionSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // إنشاء جميع الصلاحيات المطلوبة
        $permissions = [
            // صلاحيات لوحة التحكم والتحليلات
            ['name' => 'view_dashboard', 'description' => __('permissions.view_dashboard')],
            ['name' => 'view_analysis', 'description' => __('permissions.view_analysis')],

            // صلاحيات إدارة العملاء
            ['name' => 'view_leads_sources', 'description' => __('permissions.view_leads_sources')],
            ['name' => 'view_leads_timeline', 'description' => __('permissions.view_leads_timeline')],
            ['name' => 'view_leads_status', 'description' => __('permissions.view_leads_status')],

            // صلاحيات إدارة الوحدات
            ['name' => 'view_units', 'description' => __('permissions.view_units')],
            ['name' => 'manage_units', 'description' => __('permissions.manage_units')],

            // صلاحيات إدارة المواعيد
            ['name' => 'view_appointments', 'description' => __('permissions.view_appointments')],
            ['name' => 'manage_appointments', 'description' => __('permissions.manage_appointments')],

            // صلاحيات إدارة المكالمات
            ['name' => 'view_calls', 'description' => __('permissions.view_calls')],
            ['name' => 'manage_calls', 'description' => __('permissions.manage_calls')],

            // صلاحيات الحملات التسويقية
            ['name' => 'view_campaigns', 'description' => __('permissions.view_campaigns')],
            ['name' => 'manage_campaigns', 'description' => __('permissions.manage_campaigns')],

            // صلاحيات إدارة المستخدمين
            ['name' => 'view_users', 'description' => __('permissions.view_users')],
            ['name' => 'create_users', 'description' => __('permissions.create_users')],
            ['name' => 'edit_users', 'description' => __('permissions.edit_users')],
            ['name' => 'delete_users', 'description' => __('permissions.delete_users')],

            // صلاحيات إدارة الأدوار
            ['name' => 'view_roles', 'description' => __('permissions.view_roles')],
            ['name' => 'create_roles', 'description' => __('permissions.create_roles')],
            ['name' => 'edit_roles', 'description' => __('permissions.edit_roles')],
            ['name' => 'delete_roles', 'description' => __('permissions.delete_roles')],

            // صلاحيات المشاريع والتقدم
            ['name' => 'view_projects', 'description' => __('permissions.view_projects')],
            ['name' => 'edit_projects', 'description' => __('permissions.edit_projects')],
            ['name' => 'create_projects', 'description' => __('permissions.create_projects')],
            ['name' => 'delete_projects', 'description' => __('permissions.delete_projects')],
            ['name' => 'view_project_progress', 'description' => __('permissions.view_project_progress')],
            ['name' => 'manage_project_progress', 'description' => __('permissions.manage_project_progress')],

            // صلاحيات خطط المشاريع
            ['name' => 'view_project_plans', 'description' => __('permissions.view_project_plans')],
            ['name' => 'manage_project_plans', 'description' => __('permissions.manage_project_plans')],

            // صلاحيات التقارير الأساسية
            ['name' => 'view_reports', 'description' => __('permissions.view_reports')],
            ['name' => 'generate_reports', 'description' => __('permissions.generate_reports')],

            // صلاحيات تقارير حالة الوحدات
            ['name' => 'view_unit_status_reports', 'description' => __('permissions.view_unit_status_reports')],
            ['name' => 'view_unit_status_report', 'description' => __('permissions.view_unit_status_report')],
            ['name' => 'view_unit_stages_report', 'description' => __('permissions.view_unit_stages_report')],
            ['name' => 'view_unit_statistics_by_stage', 'description' => __('permissions.view_unit_statistics_by_stage')],

            // صلاحيات التقارير الإضافية
            ['name' => 'view_additional_reports', 'description' => __('permissions.view_additional_reports')],
            ['name' => 'view_unit_report', 'description' => __('permissions.view_unit_report')],
            ['name' => 'view_source_report', 'description' => __('permissions.view_source_report')],

            // صلاحيات التقارير الشاملة
            ['name' => 'view_comprehensive_reports', 'description' => __('permissions.view_comprehensive_reports')],
            ['name' => 'view_report_form', 'description' => __('permissions.view_report_form')],
            ['name' => 'view_report_parts', 'description' => __('permissions.view_report_parts')],
            ['name' => 'view_colored_map', 'description' => __('permissions.view_colored_map')],
            ['name' => 'view_appointments_report', 'description' => __('permissions.view_appointments_report')],
            ['name' => 'view_summary_report', 'description' => __('permissions.view_summary_report')],
            ['name' => 'view_unit_satage_report', 'description' => __('permissions.view_unit_satage_report')],
            ['name' => 'view_status_report', 'description' => __('permissions.view_status_report')],
            ['name' => 'view_unit_details_report', 'description' => __('permissions.view_unit_details_report')],
            ['name' => 'view_vpc_report', 'description' => __('permissions.view_vpc_report')],
            ['name' => 'view_unit_statistics_report', 'description' => __('permissions.view_unit_statistics_report')],
            ['name' => 'view_targeted_report', 'description' => __('permissions.view_targeted_report')],
            ['name' => 'view_unit_sales_report', 'description' => __('permissions.view_unit_sales_report')],

            // صلاحيات إدارة المبيعات
            ['name' => 'view_sales', 'description' => __('permissions.view_sales')],
            ['name' => 'manage_sales', 'description' => __('permissions.manage_sales')],

            // صلاحيات إدارة العملاء
            ['name' => 'view_customers', 'description' => __('permissions.view_customers')],
            ['name' => 'manage_customers', 'description' => __('permissions.manage_customers')],

            // صلاحيات متقدمة
            ['name' => 'manage_settings', 'description' => __('permissions.manage_settings')],
            ['name' => 'view_analytics', 'description' => __('permissions.view_analytics')],
            ['name' => 'export_data', 'description' => __('permissions.export_data')],

            // صلاحيات إدارة النظام
            ['name' => 'view_system_management', 'description' => __('permissions.view_system_management')],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name']],
                ['description' => $permission['description']]
            );
        }

        // إنشاء الأدوار الأساسية
        $roles = [
            [
                'name' => 'super_admin',
                'description' => 'مدير النظام - لديه جميع الصلاحيات'
            ],
            [
                'name' => 'admin',
                'description' => 'مدير - يمتلك معظم الصلاحيات الإدارية'
            ],
            [
                'name' => 'project_manager',
                'description' => 'مدير المشاريع - يمتلك صلاحيات إدارة المشاريع والتقارير'
            ],
            [
                'name' => 'sales_manager',
                'description' => 'مدير المبيعات - يمتلك صلاحيات إدارة المبيعات والعملاء'
            ],
            [
                'name' => 'sales_agent',
                'description' => 'مندوب مبيعات - يمتلك صلاحيات أساسية للمبيعات'
            ],
            [
                'name' => 'viewer',
                'description' => 'مشاهد - يمتلك صلاحيات العرض فقط'
            ],
            [
                'name' => 'report_viewer',
                'description' => 'مشاهد التقارير - يمتلك صلاحيات عرض التقارير فقط'
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }

        // تعيين الصلاحيات للأدوار

        // Super Admin - جميع الصلاحيات
        $superAdmin = Role::where('name', 'super_admin')->first();
        $superAdmin->permissions()->sync(Permission::pluck('id')->toArray());

        // Admin - معظم الصلاحيات الإدارية
        $admin = Role::where('name', 'admin')->first();
        $adminPermissions = Permission::whereNotIn('name', [
            // يمكن استثناء بعض الصلاحيات إذا لزم الأمر
        ])->pluck('id')->toArray();
        $admin->permissions()->sync($adminPermissions);

        // Project Manager - مدير المشاريع
        $projectManager = Role::where('name', 'project_manager')->first();
        $projectManagerPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_analysis',
            'view_projects', 'edit_projects', 'create_projects',
            'view_project_progress', 'manage_project_progress',
            'view_project_plans', 'manage_project_plans',
            'view_reports', 'generate_reports',
            'view_unit_status_reports', 'view_unit_status_report', 'view_unit_stages_report', 'view_unit_statistics_by_stage',
            'view_additional_reports', 'view_unit_report', 'view_source_report',
            'view_comprehensive_reports', 'view_report_form', 'view_report_parts',
            'view_colored_map', 'view_appointments_report', 'view_summary_report',
            'view_unit_satage_report', 'view_status_report', 'view_unit_details_report',
            'view_vpc_report', 'view_unit_statistics_report', 'view_targeted_report', 'view_unit_sales_report',
            'view_analytics', 'export_data'
        ])->pluck('id')->toArray();
        $projectManager->permissions()->sync($projectManagerPermissions);

        // Sales Manager - مدير المبيعات
        $salesManager = Role::where('name', 'sales_manager')->first();
        $salesManagerPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_analysis',
            'view_leads_sources', 'view_leads_timeline', 'view_leads_status',
            'view_units', 'manage_units',
            'view_appointments', 'manage_appointments',
            'view_calls', 'manage_calls',
            'view_campaigns', 'manage_campaigns',
            'view_sales', 'manage_sales',
            'view_customers', 'manage_customers',
            'view_reports', 'generate_reports',
            'view_additional_reports', 'view_unit_report', 'view_source_report',
            'view_comprehensive_reports',
            'export_data'
        ])->pluck('id')->toArray();
        $salesManager->permissions()->sync($salesManagerPermissions);

        // Sales Agent - مندوب مبيعات
        $salesAgent = Role::where('name', 'sales_agent')->first();
        $salesAgentPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_leads_sources', 'view_leads_timeline', 'view_leads_status',
            'view_units',
            'view_appointments', 'manage_appointments',
            'view_calls', 'manage_calls',
            'view_sales',
            'view_customers',
            'view_reports',
            'view_additional_reports', 'view_unit_report'
        ])->pluck('id')->toArray();
        $salesAgent->permissions()->sync($salesAgentPermissions);

        // Viewer - مشاهد
        $viewer = Role::where('name', 'viewer')->first();
        $viewerPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_analysis',
            'view_projects',
            'view_reports',
            'view_unit_status_reports', 'view_unit_status_report'
        ])->pluck('id')->toArray();
        $viewer->permissions()->sync($viewerPermissions);

        // Report Viewer - مشاهد التقارير
        $reportViewer = Role::where('name', 'report_viewer')->first();
        $reportViewerPermissions = Permission::whereIn('name', [
            'view_dashboard',
            'view_reports',
            'view_unit_status_reports', 'view_unit_status_report', 'view_unit_stages_report', 'view_unit_statistics_by_stage',
            'view_additional_reports', 'view_unit_report', 'view_source_report',
            'view_comprehensive_reports'
        ])->pluck('id')->toArray();
        $reportViewer->permissions()->sync($reportViewerPermissions);

        // تعيين دور super_admin للمستخدم الأول إذا لم يكن لديه دور
        $user = User::first();
        if ($user && !$user->roles()->exists()) {
            $user->assignRole('super_admin');
        }

        // تعيين دور admin للمستخدم الثاني إذا موجود
        $secondUser = User::skip(1)->first();
        if ($secondUser && !$secondUser->roles()->exists()) {
            $secondUser->assignRole('admin');
        }
    }
}
