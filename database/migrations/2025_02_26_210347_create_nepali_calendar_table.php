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
        Schema::create('nepali_calendar', function (Blueprint $table) {
            $table->id();
            $table->integer('bs_year'); // Nepali year (e.g., 2070)
            $table->integer('month');   // Nepali month (1–12)
            $table->integer('days');    // Days in that month
            $table->date('start_date'); // Gregorian start date of the month
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nepali_calendar');
    }
};
