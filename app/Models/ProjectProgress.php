<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectProgress extends Model
{
    use HasFactory;
    protected $table = 'project_progresses';
    protected $fillable = ['user_id', 'site', 'progress_percentage'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
