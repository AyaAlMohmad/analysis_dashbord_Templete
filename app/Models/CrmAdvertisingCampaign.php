<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmAdvertisingCampaign extends Model
{
    use HasFactory;
    protected $table = 'crm_advertising_campaigns';
    protected $fillable = [
        'site',
        'name',
        'from_date',
        'end_date',
        'source',
        'leads_count',
        'leads_reserved',
        'leads_contracted',
        'leads_contacted',
        'leads_visits',
        'cpl',
        'total_cpl',
        'impression',
        'clicks',
        'cpc',
        'ctr',
        'cpm',
    ];

}
