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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('addressable_id'); // Polymorphic relation ID
            $table->string('addressable_type'); // Polymorphic relation type
            $table->string('province_id');
            $table->string('district_id');
            $table->string('localLevel_id');
            $table->integer('ward_no')->nullable(); //
            $table->string('street_name')->nullable(); 
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
