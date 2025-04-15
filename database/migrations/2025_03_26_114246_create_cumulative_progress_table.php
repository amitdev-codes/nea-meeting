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
        Schema::create('cumulative_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fiscal_year_id')->constrained('mst_fiscal_years')->onDelete('cascade');
            $table->date('project_start_date');
            $table->date('project_end_date');
            $table->decimal('total_estimated_expenditure', 15, 2); // Total budget
            $table->decimal('total_given_expenditure', 15, 2);     // Spent amount
            $table->decimal('total_budget', 15, 2);               // GAFSP total budget
            $table->decimal('total_disbursed', 15, 2);  
            $table->decimal('total_group_formed_target', 15, 2); 
            $table->boolean('status')->default(false); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cumulative_progress');
    }
};
