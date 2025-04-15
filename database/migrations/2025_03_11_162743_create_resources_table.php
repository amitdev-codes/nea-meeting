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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., "Users", "Roles"
            $table->string('icon')->nullable(); // e.g., "user", "shield"
            $table->string('type')->default('resource'); // 'resource', 'route', 'url'
            $table->string('route_name')->nullable(); // e.g., 'users.index'
            $table->string('url')->nullable(); // e.g., '/users'
            $table->boolean('is_menu')->default(false);  // Flag for menu items (future use)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
