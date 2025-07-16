<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    use HasFactory;
    protected $fillable = ['report_id', 'site_id', 'section_id', 'data'];
    protected $casts = [
        'data' => 'json',
    ];

    public function report()
    {
        return $this->belongsTo(ComprehensiveReport::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function section()
    {
        return $this->belongsTo(ReportSection::class);
    }
}
