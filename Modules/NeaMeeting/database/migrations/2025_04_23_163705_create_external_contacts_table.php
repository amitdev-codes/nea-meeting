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
        Schema::create('external_contacts', function (Blueprint $table) {
            $table->id();
            $table->morphs('contactable'); // Creates contactable_id and contactable_type
            $table->string('name')->nullable(); // Added name field
            $table->string('email')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->string('office_name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_contacts');
    }
};
