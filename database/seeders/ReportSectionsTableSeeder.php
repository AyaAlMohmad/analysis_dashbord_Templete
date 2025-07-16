<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReportSection;

class ReportSectionsTableSeeder extends Seeder
{
    public function run()
    {
        $sections = [
            'colored_map',
            'reserved_report',
            'contracts_report',
            'status_item',
            'project_summary',
            'unitStages',
            'unitStatisticsByStage',
            'visits_payments_contracts',
            'disinterest_reasons',
            'unit_sales',
            'source_stats',
            'monthly_appointments',
            'targeted_report',
        ];

        foreach ($sections as $section) {
            ReportSection::firstOrCreate(['name' => $section]);
        }
    }
}
