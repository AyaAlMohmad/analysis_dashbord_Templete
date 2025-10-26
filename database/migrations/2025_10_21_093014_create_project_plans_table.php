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
        Schema::create('project_plans', function (Blueprint $table) {
            $table->id();
            $table->string('item_number', 10)->nullable();
            $table->string('item_name')->nullable();
            $table->enum('item_type', ['section', 'main', 'sub'])->default('main');
            $table->text('requirements')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
                $table->string('updated_end_date')->nullable()->change();
            $table->string('duration', 50)->nullable();
            $table->string('department', 100)->nullable();
            $table->string('responsible', 100)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('parent_id')->nullable()->constrained('project_plans')->onDelete('cascade');
            $table->tinyInteger('level')->default(0);
            $table->string('status_class', 50)->default('status-notstarted');
            $table->integer('sort_order')->default(0);
            $table->string('parent_section')->nullable();
            $table->timestamps();

            // فهارس لتحسين الأداء
            $table->index('parent_id');
            $table->index('item_type');
            $table->index('level');
            $table->index('status_class');
            $table->index('sort_order');
            $table->index('parent_section');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_plans');
    }
};
