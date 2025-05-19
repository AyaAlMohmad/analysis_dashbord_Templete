<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ItemLog;

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
            \Log::error('Error processing logs from '.$site.': '.$e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: '.$e->getMessage());
        }
    }

    private function processLog($log, $site)
    {
        try {
            ItemLog::updateOrCreate(
                ['log_id' => $log['id'], 'site' => $site],
                [
                    'table_name' => $log['table_name'],
                    'record_id' => $log['record_id'],
                    'action' => $log['action'],
                    'data_old' => $log['data_old'] ? json_decode($log['data_old'], true) : null,
                    'data_new' => $log['data_new'] ? json_decode($log['data_new'], true) : null,
                    'user_id' => $log['user_id'],
                    'created_at' => $log['created_at'],
                    'changed_by' => auth()->user()->name,
                ]
            );
        } catch (\Exception $e) {
            \Log::error('Error saving log '.$log['id'].': '.$e->getMessage());
        }
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
        $dataNew = $log->data_new ?? [];
        $dataOld = $log->data_old ?? [];

        foreach (array_keys(array_merge($dataNew, $dataOld)) as $field) {
            if (!isset($fieldCounts[$field])) {
                $fieldCounts[$field] = 0;
            }
            $fieldCounts[$field]++;
        }
    }

    return view('items.statistics', [
        'dailyCounts' => $modificationsPerDay,
        'fieldCounts' => $fieldCounts,
        'site' => $site,
    ]);
}

}
