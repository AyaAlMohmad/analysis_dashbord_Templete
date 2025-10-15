<?php

namespace App\Http\Controllers;

use App\Models\CollLogsLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class CallLogsLogController extends Controller
{
    public function log(Request $request, $site)
    {
        $sites = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/activity-logs?table=call_logs',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/activity-logs?table=call_logs',
            'jeddah' => 'https://crm.azyanjeddah.com/api/activity-logs?table=call_logs',
        ];

        if (!isset($sites[$site])) {
            return redirect()->back()->with('error', 'Site not found');
        }

        try {
            $response = Http::timeout(30)
                ->retry(3, 5000)
                ->get($sites[$site]);

            if ($response->successful()) {
                $data = $response->json();

                if (!isset($data['logs']) || !is_array($data['logs'])) {
                    return redirect()->back()->with('error', 'Invalid data structure');
                }

                foreach ($data['logs'] as $log) {
                    $this->processLog($log, $site);
                }

                return redirect()->route('admin.call.logs', ['site' => $site])
                    ->with('success', 'Data updated successfully');
            }

            return redirect()->back()->with('error', 'Failed to fetch data: '.$response->status());

        } catch (\Exception $e) {
            Log::error('Error processing logs from '.$site.': '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: '.$e->getMessage());
        }
    }

private function processLog($log, $site)
{
    try {
        // Validate required fields
        $requiredFields = ['id', 'table_name', 'record_id', 'action', 'user_id', 'created_at'];
        foreach ($requiredFields as $field) {
            if (!isset($log[$field])) {
                Log::warning("Missing required field '{$field}' in log: " . json_encode($log));
                return false;
            }
        }

        $dataNew = $this->parseJsonField($log['data_new'] ?? '');
        $dataOld = $this->parseJsonField($log['data_old'] ?? '');

        // Extract user name - التركيز على الاسم العربي فقط
        $userName = $this->extractUserName($log);

        CollLogsLog::updateOrCreate(
            ['log_id' => $log['id'], 'site' => $site],
            [
                'table_name' => $log['table_name'],
                'record_id' => $log['record_id'],
                'action' => $log['action'],
                'data_old' => $dataOld,
                'data_new' => $dataNew,
                'user_id' => $log['user_id'],
                'created_at' => $log['created_at'],
                'changed_by' => auth()->user()->name,
                'user_name' => $userName, // سيتم تخزين الاسم العربي فقط
            ]
        );

        return true;
    } catch (\Exception $e) {
        Log::error('Error saving log '.($log['id'] ?? 'unknown').': '.$e->getMessage());
        return false;
    }
}

 private function extractUserName($log)
{
    // Try to get user name from user_full_name field
    if (!empty($log['user_full_name'])) {
        $userFullName = $log['user_full_name'];

        // الحالة 1: إذا كان JSON يحتوي على كائنات منفصلة للإنجليزية والعربية
        // مثال: {"en":"Ahmed","ar":"احمد"} {"en":"Al Jawi","ar":"الجاوي"}
        if (preg_match_all('/\{"en":"[^"]*","ar":"([^"]*)"\}/', $userFullName, $matches)) {
            $arabicNames = [];
            foreach ($matches[1] as $arabicName) {
                if (!empty($arabicName)) {
                    $arabicNames[] = $arabicName;
                }
            }
            if (!empty($arabicNames)) {
                return implode(' ', $arabicNames);
            }
        }

        // الحالة 2: إذا كان JSON عادي يحتوي على حقل "ar"
        if (is_string($userFullName) && (strpos($userFullName, '{') === 0 || strpos($userFullName, '[') === 0)) {
            $decoded = json_decode($userFullName, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // البحث عن الاسم العربي في الهيكل
                $arabicName = $this->findArabicName($decoded);
                if ($arabicName) {
                    return $arabicName;
                }
            }
        }

        // الحالة 3: إذا كان النص يحتوي على أسماء عربية مباشرة (كحالة fallback)
        return $this->extractArabicText($userFullName);
    }

    // Fall back to user_email if available
    if (!empty($log['user_email'])) {
        return $log['user_email'];
    }

    return null;
}

private function findArabicName($data)
{
    if (is_array($data)) {
        // إذا كان هناك مفتاح "ar" مباشرة
        if (isset($data['ar']) && !empty($data['ar'])) {
            return $data['ar'];
        }

        // البحث في جميع عناصر المصفوفة
        foreach ($data as $value) {
            if (is_array($value)) {
                $result = $this->findArabicName($value);
                if ($result) {
                    return $result;
                }
            }
        }
    }

    return null;
}

private function extractArabicText($text)
{
    // استخراج النص العربي باستخدام regex للنطاق العربي Unicode
    preg_match_all('/[\x{0600}-\x{06FF}\x{0750}-\x{077F}\x{08A0}-\x{08FF}\x{FB50}-\x{FDFF}\x{FE70}-\x{FEFF} ]+/u', $text, $matches);

    $arabicParts = [];
    foreach ($matches[0] as $match) {
        $trimmed = trim($match);
        if (!empty($trimmed) && strlen($trimmed) > 1) {
            $arabicParts[] = $trimmed;
        }
    }

    if (!empty($arabicParts)) {
        return implode(' ', $arabicParts);
    }

    // إذا لم يتم العثور على نص عربي، إرجاع النص الأصلي
    return $text;
}

    private function parseJsonField($fieldData)
    {
        if (empty($fieldData)) {
            return null;
        }

        // Remove surrounding quotes if present
        $cleanedData = trim($fieldData, '"');

        // Handle empty JSON objects/arrays
        if ($cleanedData === '[]' || $cleanedData === '{}' || empty($cleanedData)) {
            return null;
        }

        $decoded = json_decode($cleanedData, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('JSON decode error: ' . json_last_error_msg() . ' for data: ' . substr($cleanedData, 0, 100));
            return null;
        }

        return $decoded;
    }

    public function showLog($site)
    {
        $logs = CollLogsLog::where('site', $site)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('call_logs.logs', compact('logs', 'site'));
    }

    public function showStatistics($site)
    {
        // Use database aggregation for better performance
        $dailyCounts = CollLogsLog::where('site', $site)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->pluck('count', 'date');

        // For field counts, we'll still need to process individually
        // but we can limit to recent logs for better performance
        $recentLogs = CollLogsLog::where('site', $site)
            ->orderBy('created_at', 'desc')
            ->limit(1000) // Limit for performance
            ->get();

        $fieldCounts = [];
        foreach ($recentLogs as $log) {
            $this->countFields($log->data_new ?? [], $fieldCounts);
            $this->countFields($log->data_old ?? [], $fieldCounts);
        }

        // Sort by count descending
        arsort($fieldCounts);

        return view('call_logs.statistics', [
            'dailyCounts' => $dailyCounts,
            'fieldCounts' => $fieldCounts,
            'site' => $site,
        ]);
    }

    private function countFields($data, &$fieldCounts)
    {
        if (!is_array($data)) {
            return;
        }

        foreach (array_keys($data) as $field) {
            if (!isset($fieldCounts[$field])) {
                $fieldCounts[$field] = 0;
            }
            $fieldCounts[$field]++;
        }
    }

}
