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
        Schema::create('meeting_recurrences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meeting_id')->constrained()->cascadeOnDelete();
            $table->string('recurrence_pattern', 50);
            $table->integer('recurrence_interval')->default(1);
            $table->string('days_of_week', 50)->nullable();
            $table->integer('day_of_month')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('occurrences')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_recurrence');
    }
};
