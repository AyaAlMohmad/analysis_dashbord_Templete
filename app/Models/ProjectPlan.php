<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_number',
        'item_name',
        'requirements',
        'start_date',
        'end_date',
        'updated_end_date',
        'duration',
        'department',
        'responsible',
        'notes',
        'item_type',
        'parent_id',
        'parent_section',
        'sort_order',
        'status_class',
        'site',
    ];

    // إزالة الـ casting للحقول التاريخية لتجنب المشاكل
    protected $casts = [
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
        'updated_end_date' => 'date:Y-m-d',
    ];

    // دالة لمعالجة updated_end_date بشكل آمن
    public function getDisplayUpdatedEndDateAttribute()
    {
        if (empty($this->updated_end_date) || $this->updated_end_date === '-') {
            return '-';
        }

        // إذا كانت القيمة نصية (تم، لم تبدأ، إلخ)
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $this->updated_end_date)) {
            return $this->updated_end_date;
        }

        // إذا كانت تاريخ
        try {
            return \Carbon\Carbon::parse($this->updated_end_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return $this->updated_end_date;
        }
    }

    public function getSafeStartDateAttribute()
    {
        if (empty($this->start_date) || $this->start_date === '-') {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($this->start_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return '-';
        }
    }
  public function getRequirementsStatusAttribute()
    {
        if (empty($this->status_class)) {
            return 'notstarted';
        }

        return str_replace('status-', '', $this->status_class);
    }
    public function getSafeEndDateAttribute()
    {
        if (empty($this->end_date) || $this->end_date === '-') {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse($this->end_date)->format('Y-m-d');
        } catch (\Exception $e) {
            return '-';
        }
    }

    // العلاقة مع العناصر الفرعية
    public function children()
    {
        return $this->hasMany(ProjectPlan::class, 'parent_id')->orderBy('sort_order');
    }

    public function parent()
    {
        return $this->belongsTo(ProjectPlan::class, 'parent_id');
    }
}
