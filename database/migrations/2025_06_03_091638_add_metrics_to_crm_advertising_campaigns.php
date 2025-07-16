<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('crm_advertising_campaigns', function (Blueprint $table) {
            $table->integer('impression')->default(0);
            $table->integer('clicks')->default(0);
            $table->decimal('cpc', 10, 2)->default(0);
            $table->decimal('ctr', 10, 2)->default(0);
            $table->decimal('cpm', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('crm_advertising_campaigns', function (Blueprint $table) {
            $table->dropColumn(['impression', 'clicks', 'cpc', 'ctr', 'cpm']);
        });
    }

};
