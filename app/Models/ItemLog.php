<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    protected $table = 'item_logs';

  
    protected $fillable = [
        'log_id',
        'site',
        'table_name',
        'record_id',
        'action',
        'data_old',
        'data_new',
        'user_id',
        'created_at',
        'changed_by'
    ];

  
    protected $dates = [
        'created_at',
    ];


    protected $casts = [
        'data_old' => 'array',
        'data_new' => 'array',
    ];
}
