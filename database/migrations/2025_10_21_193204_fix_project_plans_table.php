<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('project_plans', function (Blueprint $table) {
            // تغيير نوع updated_end_date إلى string
            $table->string('updated_end_date')->nullable()->change();

            // إضافة حقل level إذا كان غير موجود
            if (!Schema::hasColumn('project_plans', 'level')) {
                $table->integer('level')->default(0)->after('parent_id');
            }
        });
    }

    public function down()
    {
        Schema::table('project_plans', function (Blueprint $table) {
            $table->date('updated_end_date')->nullable()->change();
        });
    }
};
