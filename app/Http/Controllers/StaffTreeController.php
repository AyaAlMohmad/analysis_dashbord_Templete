<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class StaffTreeController extends Controller
{
    protected $reportView = 'staff_tree.index';
    protected $formRouteName = 'admin.comprehensive.staff-tree.process';

    public function index(Request $request)
    {
        $albashaerStaffTree = $this->getAlbashaerStaffTreeReport();
        $dhahranStaffTree = $this->getDhahranStaffTreeReport();
        $jeddahStaffTree = $this->getJeddahStaffTreeReport();
        $alfursanStaffTree = $this->getAlfursanStaffTreeReport();

        return view($this->reportView, [
            'albashaerStaffTree' => $albashaerStaffTree,
            'dhahranStaffTree' => $dhahranStaffTree,
            'jeddahStaffTree' => $jeddahStaffTree,
            'alfursanStaffTree' => $alfursanStaffTree,
        ]);
    }

    private function getAlbashaerStaffTreeReport()
    {
        try {
            $response = Http::get('https://crm.azyanalbashaer.com/api/custom-reports/api_staff_tree');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $this->transformStaffData($data, 'البشائر');
                }
            }
        } catch (\Exception $e) {
            Log::error("Albashaer Staff Tree API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch staff tree report from Albashaer API',
            'data' => [],
        ];
    }

    private function getDhahranStaffTreeReport()
    {
        try {
            $response = Http::get('https://crm.azyanaldhahran.com/api/custom-reports/api_staff_tree');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $this->transformStaffData($data, 'الظهران');
                }
            }
        } catch (\Exception $e) {
            Log::error("Dhahran Staff Tree API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch staff tree report from Dhahran API',
            'data' => [],
        ];
    }

    private function getJeddahStaffTreeReport()
    {
        try {
            $response = Http::get('https://crm.azyanjeddah.com/api/custom-reports/api_staff_tree');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $this->transformStaffData($data, 'جدة');
                }
            }
        } catch (\Exception $e) {
            Log::error("Jeddah Staff Tree API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch staff tree report from Jeddah API',
            'data' => [],
        ];
    }

    private function getAlfursanStaffTreeReport()
    {
        try {
            $response = Http::get('https://crm.azyanalfursan.com/api/custom-reports/api_staff_tree');

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] ?? false) {
                    return $this->transformStaffData($data, 'الفرسان');
                }
            }
        } catch (\Exception $e) {
            Log::error("Alfursan Staff Tree API Error: " . $e->getMessage());
        }

        return [
            'status' => false,
            'message' => 'Failed to fetch staff tree report from Alfursan API',
            'data' => [],
        ];
    }

    private function transformStaffData($apiData, $projectName)
    {
        // البيانات بتكون في apiData['data']['staff'] و apiData['data']['roles']
        $staffData = $apiData['data']['staff'] ?? [];
        $rolesData = $apiData['data']['roles'] ?? [];

        logger('Staff Data Count for ' . $projectName . ': ' . count($staffData));
        logger('Roles Data Count for ' . $projectName . ': ' . count($rolesData));

        // إذا ما في بيانات، رجع مصفوفة فاضية
        if (empty($staffData)) {
            return [
                'status' => true,
                'data' => [
                    'manager' => '',
                    'manager_title' => '',
                    'service_title' => 'خدمات ' . $projectName,
                    'total_employees' => 0,
                    'employees' => [],
                    'positions' => [],
                    'departments' => [],
                ]
            ];
        }

        // استخراج البيانات الحقيقية
        $manager = $this->extractManager($staffData);
        $positions = $this->groupEmployeesByRole($staffData, $rolesData);
        $employees = $this->extractEmployeeNames($staffData);
        $departments = $this->extractDepartmentsFromRoles($rolesData);

        return [
            'status' => true,
            'data' => [
                'manager' => $manager['name'] ?? '',
                'manager_title' => $manager['role_name'] ?? '',
                'service_title' => 'خدمات ' . $projectName,
                'total_employees' => count($staffData),
                'employees' => $employees,
                'positions' => $positions,
                'departments' => $departments,
            ]
        ];
    }

    private function extractManager($staffData)
    {
        // خذ أول موظف نشط كمدير
        foreach ($staffData as $employee) {
            if (($employee['active'] ?? '0') === '1') {
                $name = $this->getArabicName($employee);
                if (!empty(trim($name))) {
                    return [
                        'name' => $name,
                        'role_name' => $employee['role_name'] ?? 'مدير'
                    ];
                }
            }
        }
        
        return ['name' => '', 'role_name' => ''];
    }

    private function getArabicName($employee)
    {
        try {
            $firstNameJson = $employee['firstname'] ?? '{"ar": ""}';
            $lastNameJson = $employee['lastname'] ?? '{"ar": ""}';
            
            $firstNameArray = json_decode($firstNameJson, true) ?? [];
            $lastNameArray = json_decode($lastNameJson, true) ?? [];
            
            $firstName = $firstNameArray['ar'] ?? $employee['firstname'] ?? '';
            $lastName = $lastNameArray['ar'] ?? $employee['lastname'] ?? '';
            
            return trim($firstName . ' ' . $lastName);
        } catch (\Exception $e) {
            return $employee['firstname'] . ' ' . $employee['lastname'];
        }
    }

    private function groupEmployeesByRole($staffData, $rolesData)
    {
        $positions = [];
        
        // إنشاء lookup table للأدوار
        $rolesLookup = [];
        foreach ($rolesData as $role) {
            $rolesLookup[$role['roleid']] = $role['name'];
        }
        
        foreach ($staffData as $employee) {
            $roleId = $employee['role'];
            $roleName = $rolesLookup[$roleId] ?? $employee['role_name'] ?? 'موظف';
            $employeeName = $this->getArabicName($employee);
            
            if (empty(trim($employeeName))) {
                continue; // تخطى إذا الاسم فاضي
            }
            
            if (!isset($positions[$roleName])) {
                $positions[$roleName] = [];
            }
            
            $positions[$roleName][] = $employeeName;
        }
        
        return $positions;
    }

    private function extractEmployeeNames($staffData)
    {
        $names = [];
        
        foreach ($staffData as $employee) {
            $name = $this->getArabicName($employee);
            if (!empty(trim($name))) {
                $names[] = $name;
            }
        }
        
        return array_slice($names, 0, 10); // خذ أول 10 موظفين فقط
    }

    private function extractDepartmentsFromRoles($rolesData)
    {
        $departments = [];
        
        foreach ($rolesData as $role) {
            $roleName = $role['name'];
            if (!in_array($roleName, $departments)) {
                $departments[] = $roleName;
            }
        }
        
        return $departments;
    }
}