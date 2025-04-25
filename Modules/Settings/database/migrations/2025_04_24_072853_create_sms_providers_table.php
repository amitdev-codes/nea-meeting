<?php

use Illuminate\Support\Facades\DB;
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
        Schema::create('sms_providers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Insert default providers
        DB::table('sms_providers')->insert([
            [
                'name' => 'Sparrow SMS',
                'code' => 'sparrow',
                'description' => 'Sparrow SMS Nepal',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Generic Provider',
                'code' => 'generic',
                'description' => 'Generic SMS Provider',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_providers');
    }
};
