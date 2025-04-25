<?php

use App\Enums\MeetingType;
use App\Enums\MeetingStatus;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->foreignId('fiscal_year_id')->nullable()->constrained('mst_fiscal_years')->nullOnDelete();
            $table->text('description')->nullable();
            // $table->enum('meeting_type', MeetingType::values())->default(MeetingType::REGULAR->value);
            $table->enum('meeting_type', MeetingType::values())->nullable();
            $table->string('meeting_date')->nullable();
            $table->date('meeting_date_ad')->nullable();
            $table->dateTime('start_time'); // Changed from timestamp to dateTime
            $table->dateTime('end_time')->nullable();   // Changed from timestamp to dateTime
            $table->string('meeting_location',255)->nullable();
            $table->foreignId('meeting_room_id')->nullable()->constrained()->nullOnDelete();
            $table->boolean('is_virtual')->default(false);
            $table->string('virtual_meeting_link', 255)->nullable();
            $table->json('organizations')->nullable();
            $table->enum('status', MeetingStatus::values())->default(MeetingStatus::Scheduled->value);
            $table->boolean('is_external')->default(false);
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('remarks')->nullable();
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
