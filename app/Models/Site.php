<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'logo_path_white','logo_path','map_path'];
    public function reportData()
{
    return $this->hasMany(ReportData::class);
}

}
