<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\AppointmentLog;
use Illuminate\Support\Facades\Log;

class AppointmentLogController extends Controller
{
    public function log(Request $request, $site)
    {
        $sites = [
            'dhahran' => 'https://crm.azyanaldhahran.com/api/activity-logs?table=appointly_appointments',
            'bashaer' => 'https://crm.azyanalbashaer.com/api/activity-logs?table=appointly_appointments',
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
                    return redirect()->back()->with('error', 'Incorrect data structure');
                }

                foreach ($data['logs'] as $log) {
                    $this->processLog($log, $site);
                }

                return redirect()->route('admin.appointments.logs', ['site' => $site])
                    ->with('success', 'Data updated successfully');
            }

            return redirect()->back()->with('error', 'Failed to fetch data: ' . $response->status());
        } catch (\Exception $e) {
            Log::error('Error processing logs for ' . $site . ': ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    private function processLog($log, $site)
    {
        try {
            $dataNew = null;
            if (!empty($log['data_new'])) {

                $dataNewStr = trim($log['data_new'], '"');
                $dataNew = json_decode($dataNewStr, true);
            }
            $dataOld = null;
            if (!empty($log['data_old'])) {

                $dataOldStr = trim($log['data_old'], '"');
                $dataOld = json_decode($dataOldStr, true);
            }
            AppointmentLog::updateOrCreate(
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
                ]
            );
        } catch (\Exception $e) {
            Log::error('Error saving log ID ' . $log['id'] . ': ' . $e->getMessage());
        }
    }

    public function showLog($site)
    {
        $logs = AppointmentLog::where('site', $site)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        // dd($logs);
        return view('appointments.logs', compact('logs', 'site'));
    }
    public function showStatistics($site)
    {
        $logs = AppointmentLog::where('site', $site)->get();

        $actionCounts = $logs->groupBy('action')->map->count();

        $dailyCounts = $logs->groupBy(function ($log) {
            return \Carbon\Carbon::parse($log->created_at)->format('Y-m-d');
        })->map->count();

        return view('appointments.statistics', compact('actionCounts', 'dailyCounts', 'site'));
    }
}
