<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortOrderDefaultToProjectPlansTable extends Migration
{
    public function up()
    {
        Schema::table('project_plans', function (Blueprint $table) {
            $table->integer('sort_order')->default(0)->change();
        });
    }

    public function down()
    {
        Schema::table('project_plans', function (Blueprint $table) {
            $table->integer('sort_order')->default(null)->change();
        });
    }
}
