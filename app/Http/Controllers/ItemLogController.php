<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ItemLog;
use Illuminate\Support\Facades\Log;

class ItemLogController extends Controller
{
    public function log(Request $request, $site)
    {
        $sites = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/activity-logs?table=tblitems',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/activity-logs?table=tblitems',
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

                return redirect()->route('admin.items.logs', ['site' => $site])
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
            // Handle double-encoded JSON strings
            $dataOld = $log['data_old'] ?? null;
            $dataNew = $log['data_new'] ?? null;

            // Clean and decode the data
            $decodedOld = $this->cleanAndDecodeJson($dataOld);
            $decodedNew = $this->cleanAndDecodeJson($dataNew);

            ItemLog::updateOrCreate(
                ['log_id' => $log['id'], 'site' => $site],
                [
                    'table_name' => $log['table_name'],
                    'record_id' => $log['record_id'],
                    'action' => $log['action'],
                    'data_old' => $decodedOld,
                    'data_new' => $decodedNew,
                    'user_id' => $log['user_id'],
                    'created_at' => $log['created_at'],
                    'changed_by' => auth()->user()->name,
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error saving log '.$log['id'].': '.$e->getMessage());
        }
    }

    private function cleanAndDecodeJson($data)
    {
        if (empty($data)) {
            return null;
        }

        // If it's already an array, return as is
        if (is_array($data)) {
            return $data;
        }

        // Remove extra escaping if present
        $data = stripslashes($data);

        // Decode the JSON
        $decoded = json_decode($data, true);

        // If decoding failed, try to clean the string and decode again
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = trim($data, '"');
            $decoded = json_decode($data, true);
        }

        return $decoded ?? null;
    }

    public function showLog($site)
    {
        $logs = ItemLog::where('site', $site)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('items.logs', compact('logs', 'site'));
    }
    public function statistics($site)
    {
        $logs = ItemLog::where('site', $site)->get();

        $modificationsPerDay = $logs->groupBy(function ($log) {
            return \Carbon\Carbon::parse($log->created_at)->format('Y-m-d');
        })->map(function ($group) {
            return $group->count();
        });

        $fieldCounts = [];

        foreach ($logs as $log) {
            // Ensure data_new and data_old are arrays
            $dataNew = is_array($log->data_new) ? $log->data_new : (array) $log->data_new;
            $dataOld = is_array($log->data_old) ? $log->data_old : (array) $log->data_old;

            // Only proceed if at least one of them has data
            if (!empty($dataNew) || !empty($dataOld)) {
                foreach (array_keys(array_merge($dataNew, $dataOld)) as $field) {
                    if (!isset($fieldCounts[$field])) {
                        $fieldCounts[$field] = 0;
                    }
                    $fieldCounts[$field]++;
                }
            }
        }

        return view('items.statistics', [
            'dailyCounts' => $modificationsPerDay,
            'fieldCounts' => $fieldCounts,
            'site' => $site,
        ]);
    }

}
