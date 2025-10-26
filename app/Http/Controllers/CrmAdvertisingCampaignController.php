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
            'jeddah' => 'https://crm.azyanjeddah.com/api/advertising_campaign_api/get_source',
            'alfursan' => 'https://crm.azyanalfursan.com/api/advertising_campaign_api/get_source',
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

    public function getTags(Request $request)
    {
        $site = $request->site;

        if (!$site) {
            return response()->json([], 400);
        }

        $apiUrls = [
            'aldhahran' => 'https://crm.azyanaldhahran.com/api/Advertising_campaign/get_tag',
            'albashaer' => 'https://crm.azyanalbashaer.com/api/Advertising_campaign/get_tag',
            'jeddah' => 'https://crm.azyanjeddah.com/api/advertising_campaign_api/get_tag',
            'alfursan' => 'https://crm.azyanalfursan.com/api/advertising_campaign_api/get_tag',
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

    public function form()
    {
        $campaigns = CrmAdvertisingCampaign::all();
        return view('crm_advertising_campaign.form', ['campaigns' => $campaigns]);
    }

    public function submitCampaign(Request $request)
    {
        $request->validate([
            'site' => 'required|in:aldhahran,albashaer,jeddah,alfursan',
            'name' => 'required_without:tag_id|string|nullable',
            'tag_id' => 'required_without:name|numeric|nullable',
            'from_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:from_date',
            'source' => 'required|numeric',
            'total_amount' => 'required|numeric',
            'source_name' => 'required|string',
            'impression' => 'nullable|integer',
            'clicks' => 'nullable|integer',
        ]);

        // التحقق من أنه تم إدخال إما name أو tag_id
        if (!$request->name && !$request->tag_id) {
            return back()->withErrors(['error' => 'Either name or tag_id is required.']);
        }

        $siteMap = [
            'aldhahran' => 'https://crm.azyanaldhahran.com',
            'albashaer' => 'https://crm.azyanalbashaer.com',
            'jeddah' => 'https://crm.azyanjeddah.com',
            'alfursan' => 'https://crm.azyanalfursan.com'
        ];

        $site = $request->site;

        if (!isset($siteMap[$site])) {
            return back()->withErrors(['error' => 'Invalid site selected.']);
        }

        $baseUrl = $siteMap[$site];

        // تحديد الـ URL بناءً على الموقع
        if ($site === 'jeddah' || $site === 'alfursan') {
            $url = "$baseUrl/api/advertising_campaign_api/result";
        } else {
            $url = "$baseUrl/api/Advertising_campaign/result";
        }

        try {
            // تحضير البيانات للإرسال بناءً على الشرط
            $requestData = [
                'from_date' => $request->from_date,
                'end_date' => $request->end_date,
                'source' => $request->source,
                'total_amount' => $request->total_amount,
                'impression' => $request->impression,
                'clicks' => $request->clicks,
            ];

            // إذا كان name موجود نرسله، وإذا كان tag_id موجود نرسله
            if ($request->name) {
                $requestData['name'] = $request->name;
            } elseif ($request->tag_id) {
                $requestData['tag_id'] = $request->tag_id;
            }

            $response = Http::get($url, $requestData);

            // Handle API response
            if ($response->successful()) {
                $result = $response->json();
                $campaignData = $result['campaign'] ?? [];

                // Your existing campaign creation/update logic here...
                $leadsCount = $campaignData['leads_count'] ?? 0;
                $totalAmount = $request->total_amount;
                $cpl = $leadsCount > 0 ? $totalAmount / $leadsCount : 0;
                $impression = $request->impression ?? 0;
                $clicks = $request->clicks ?? 0;
                $cpc = $clicks > 0 ? $totalAmount / $clicks : 0;
                $ctr = $impression > 0 ? ($clicks / $impression) * 100 : 0;
                $cpm = $impression > 0 ? ($totalAmount / $impression) * 1000 : 0;

                // تحديد الاسم للحملة بناءً على الشرط
                $campaignName = $request->name;
                if (!$campaignName && $request->tag_id) {
                    // يمكنك الحصول على اسم التاغ من الـ API إذا أردت
                    $campaignName = "Tag Campaign - " . $request->tag_id;
                }

                // Find or create campaign
                $campaign = CrmAdvertisingCampaign::updateOrCreate(
                    [
                        'name' => $campaignName,
                        'from_date' => $request->from_date,
                        'total_cpl' => $totalAmount,
                    ],
                    [
                        'site' => $site,
                        'end_date' => $campaignData['end_date'] ?? $request->end_date,
                        'source' => $request->input('source_name'),
                        'tag_id' => $request->tag_id,
                        'leads_count' => $leadsCount,
                        'leads_reserved' => $campaignData['leads_reserved'] ?? 0,
                        'leads_contacted' => $campaignData['leads_contacted'] ?? 0,
                        'leads_visits' => $campaignData['leads_visits'] ?? 0,
                        'leads_contracted' => $campaignData['leads_contracted'] ?? 0,
                        'cpl' => $cpl,
                        'total_cpl' => $totalAmount,
                        'impression' => $impression,
                        'clicks' => $clicks,
                        'cpc' => $cpc,
                        'ctr' => $ctr,
                        'cpm' => $cpm,
                    ]
                );

                // Return the view with campaign data
                return view('crm_advertising_campaign.result', [
                    'result' => $campaign,
                    'site' => $site,
                ]);

            } else {
                return back()->withErrors(['error' => 'API request failed: ' . $response->status()]);
            }

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to connect to the server: ' . $e->getMessage()]);
        }
    }

    public function show(Request $request)
    {
        $campaign = CrmAdvertisingCampaign::find($request->id);
        return view('crm_advertising_campaign.result', [
            'result' => $campaign,
            'site' => $campaign->site,
        ]);
    }
}
