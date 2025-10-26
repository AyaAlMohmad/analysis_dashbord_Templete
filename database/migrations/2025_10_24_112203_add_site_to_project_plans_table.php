<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_site_to_project_plans_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteToProjectPlansTable extends Migration
{
    public function up()
    {
        Schema::table('project_plans', function (Blueprint $table) {
            $table->string('site')->default('jeddah')->after('id');
        });
    }

    public function down()
    {
        Schema::table('project_plans', function (Blueprint $table) {
            $table->dropColumn('site');
        });
    }
}
