<?php

namespace App\Http\Controllers;

use App\Models\CrmAdvertisingCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CrmAdvertisingCampaignController extends Controller
{
    public function getSources(Request $request)
    {
        $site = $request->site;

        if (!$site) {
            return response()->json([], 400);
        }

        $apiUrls = [
            'aldhahran' => 'https://crm.azyanaldhahran.com/api/Advertising_campaign/get_source',
            'albashaer' => 'https://crm.azyanalbashaer.com/api/Advertising_campaign/get_source',
        ];

        if (!isset($apiUrls[$site])) {
            return response()->json([], 404);
        }

        try {
            $response = Http::acceptJson()->get($apiUrls[$site]);

            if ($response->successful()) {
                return response()->json($response->json());
            } else {
                return response()->json([], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'API request failed.'], 500);
        }
    }

    public function form(){
$campaigns=CrmAdvertisingCampaign::all();
        return view('crm_advertising_campaign.form',['campaigns'=>$campaigns]);
    }



    public function submitCampaign(Request $request)
    {
        $request->validate([
            'site' => 'required|in:aldhahran,albashaer',
            'name' => 'required|string',
            'from_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:from_date',
            'source' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'source_name' => 'required|string',
        ]);

        $siteMap = [
            'aldhahran' => 'https://crm.azyanaldhahran.com',
            'albashaer' => 'https://crm.azyanalbashaer.com',
        ];

        $site = $request->site;
        $baseUrl = $siteMap[$site];
        $url = "$baseUrl/api/Advertising_campaign/result";

        try {
            $response = Http::get($url, [
                'name' => $request->name,
                'from_date' => $request->from_date,
                'end_date' => $request->end_date,
                'source' => $request->source,
                'total_amount' => $request->total_amount,
            ]);

            $result = $response->successful() ? $response->json() : [];
            $campaignData = $result['campaign'] ?? [];

            $leadsCount = $campaignData['leads_count'] ?? 0;
            $totalAmount = $request->total_amount;
            $cpl = $leadsCount > 0 ? $totalAmount / $leadsCount : 0;

            $existing = CrmAdvertisingCampaign::where('name', $request->name)
                ->where('from_date', $request->from_date)
                ->where('total_cpl', $totalAmount)
                ->first();

            if ($existing) {

                $existing->update([
                    'site' => $site,
                    'end_date' => $campaignData['end_date'] ?? $request->end_date,
                    'source' => $request->input('source_name'),
                    'leads_count' => $leadsCount,
                    'leads_reserved' => $campaignData['leads_reserved'] ?? 0,
                    'leads_contacted' => $campaignData['leads_contacted'] ?? 0,
                    'leads_visits' => $campaignData['leads_visits'] ?? 0,
                    'cpl' => $cpl,
                    'total_cpl' => $totalAmount,
                ]);
                $campaign = $existing;
            } else {

                $campaign = CrmAdvertisingCampaign::create([
                    'site' => $site,
                    'name' => $campaignData['name'] ?? $request->name,
                    'from_date' => $campaignData['from_date'] ?? $request->from_date,
                    'end_date' => $campaignData['end_date'] ?? $request->end_date,
                    'source' => $request->input('source_name'),
                    'leads_count' => $leadsCount,
                    'leads_reserved' => $campaignData['leads_reserved'] ?? 0,
                    'leads_contacted' => $campaignData['leads_contacted'] ?? 0,
                    'leads_visits' => $campaignData['leads_visits'] ?? 0,
                    'cpl' => $cpl,
                    'total_cpl' => $totalAmount,
                ]);
            }

            return view('crm_advertising_campaign.result', [
                'result' => $campaign,
                'site' => $site,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to connect to the server.']);
        }
    }
public function show(Request $request){
    $campaign=CrmAdvertisingCampaign::find($request->id);
    return view('crm_advertising_campaign.result',[
        'result'=>$campaign,
        'site'=>$campaign->site,
    ]);
}


}
