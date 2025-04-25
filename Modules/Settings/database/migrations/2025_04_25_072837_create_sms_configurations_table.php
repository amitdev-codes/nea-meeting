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
        Schema::create('sms_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sms_provider_id')->constrained('sms_providers');
            $table->string('api_token')->nullable();
            $table->string('api_key')->nullable();
            $table->string('api_secret')->nullable();
            $table->string('sender_id')->nullable();
            $table->string('base_url')->nullable(); 
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->json('additional_params')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_configurations');
    }
};
