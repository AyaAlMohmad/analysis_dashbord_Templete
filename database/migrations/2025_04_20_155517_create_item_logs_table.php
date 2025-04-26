<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('item_logs', function (Blueprint $table) {
            $table->id(); 
            $table->string('log_id')->unique(); 
            $table->string('site'); 
            $table->string('table_name');
            $table->string('record_id'); 
            $table->string('action');
            $table->json('data_old')->nullable(); 
            $table->json('data_new')->nullable(); 
            $table->unsignedBigInteger('user_id'); 
            $table->string('changed_by'); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_logs');
    }
};
