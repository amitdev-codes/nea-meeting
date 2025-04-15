<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**mma
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('landing_page_menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('order_id')->default(0); 
            $table->timestamps();

            // Foreign Key Constraint (if you want to enforce hierarchy)
            $table->foreign('parent_id')->references('id')->on('landing_page_menus')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landing_page_menus');
    }
};
