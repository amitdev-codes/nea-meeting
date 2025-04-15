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
        Schema::create('mst_fiscal_years', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('date_from_bs')->nullable();
            $table->string('date_to_bs')->nullable();
            $table->date('date_from_ad')->nullable();
            $table->date('date_to_ad')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('is_current')->default(false);
            $table->boolean('is_previous')->default(false);
            $table->boolean('is_next')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_fiscal_years');
    }
};
