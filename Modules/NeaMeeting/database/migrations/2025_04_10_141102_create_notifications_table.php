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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Matches the UUID format in the error
            $table->string('type'); // Required by Laravel's notification system
            $table->morphs('notifiable'); // Creates notifiable_id and notifiable_type
            $table->text('data'); // Stores notification data as JSON
            $table->timestamp('read_at')->nullable(); // Tracks if notification is read
            $table->foreignId('meeting_id')->constrained()->cascadeOnDelete(); // Your custom field
            $table->string('notification_type', 50); // Your custom field
            $table->text('message'); // Your custom field
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
