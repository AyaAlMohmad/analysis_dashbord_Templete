<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollLogsLog extends Model
{
    protected $table = 'coll_logs_logs';


       protected $fillable = [
        'log_id',
        'site',
        'table_name',
        'record_id',
        'user_name', // Add this
        'action',
        'data_old',
        'data_new',
        'user_id',
        'changed_by',
        'created_at'
    ];

    protected $casts = [
        'data_old' => 'array',
        'data_new' => 'array',
        'created_at' => 'datetime',
    ];

    public function getChangedFieldsAttribute()
    {
        if (empty($this->data_old) || empty($this->data_new)) {
            return [];
        }

        $changed = [];
        foreach ($this->data_new as $key => $newValue) {
            $oldValue = $this->data_old[$key] ?? null;
            if ($oldValue != $newValue) {
                $changed[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue
                ];
            }
        }

        return $changed;
    }
    public function getArabicUserNameAttribute()
{
    if (!$this->user_name) {
        return null;
    }

    $userNameArray = json_decode($this->user_name, true);

    if (is_array($userNameArray) && isset($userNameArray['ar'])) {
        return $userNameArray['ar'];
    }

    return $this->user_name;
}
}
