<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCrmAdvertisingCampaignsTable extends Migration
{
    public function up()
    {
        Schema::create('crm_advertising_campaigns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('site');
            $table->string('name'); // Campaign name
            $table->string('from_date');
            $table->string('end_date');
            $table->string('source');
            $table->integer('leads_count')->default(0);
            $table->integer('leads_reserved')->default(0);
            $table->integer('leads_contracted')->default(0);
            $table->integer('leads_contacted')->default(0);
            $table->integer('leads_visits')->default(0);
            $table->decimal('cpl', 10, 2)->default(0);
            $table->decimal('total_cpl', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('crm_advertising_campaigns');
    }
}
