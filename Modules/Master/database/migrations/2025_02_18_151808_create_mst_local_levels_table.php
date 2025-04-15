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
        Schema::create('mst_local_levels', function (Blueprint $table) {
            $table->id();
            $table->string('district_code')->nullable();
            $table->foreignId('district_id')->constrained('mst_districts')->cascadeOnDelete()->nullable();;
            $table->string('name');
            $table->string('name_np')->nullable();
            $table->tinyInteger('wards')->nullable();
            $table->string('code')->nullable();
            $table->boolean('status')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mst_local_levels');
    }
};
