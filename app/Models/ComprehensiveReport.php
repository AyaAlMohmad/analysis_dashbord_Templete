<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComprehensiveReport extends Model
{
    use HasFactory;
    protected $fillable = ['from_date', 'to_date'];
    public function reportData()
    {
        return $this->hasMany(ReportData::class, 'report_id');
    }

}
