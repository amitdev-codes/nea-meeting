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
        Schema::create('mst_clusters', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('cluster_type_id')->constrained('mst_cluster_types')->onDelete('cascade');
            $table->json('provinces')->nullable();
            $table->json('districts')->nullable();
            $table->json('local_levels')->nullable();
            $table->string('name');
            $table->string('name_np')->nullable();
            $table->string('description')->nullable();
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
        Schema::dropIfExists('clusters');
    }
};
