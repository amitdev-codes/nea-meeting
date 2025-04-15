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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id();
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->string('meeting_type', 50);
            $table->string('meeting_date')->nullable();
            $table->date('meeting_date_ad')->nullable();
            $table->dateTime('start_time'); // Changed from timestamp to dateTime
            $table->dateTime('end_time');   // Changed from timestamp to dateTime
            $table->string('meeting_location',255)->nullable();
            $table->foreignId('meeting_room_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_virtual')->default(false);
            $table->string('virtual_meeting_link', 255)->nullable();
            $table->string('status', 20)->default('scheduled');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
