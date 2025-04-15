<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSlidersTableAddTitleSize extends Migration
{
    public function up(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Modify existing columns to include 'p'
            $table->enum('title_size', ['p', 'h1', 'h2', 'h3', 'h4', 'h5'])->default('h1')->change();
            $table->enum('subtitle_size', ['p', 'h1', 'h2', 'h3', 'h4', 'h5'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('sliders', function (Blueprint $table) {
            // Rollback the changes if needed
            $table->enum('title_size', ['h1', 'h2', 'h3', 'h4', 'h5'])->default('h1')->change();
            $table->enum('subtitle_size', ['h1', 'h2', 'h3', 'h4', 'h5'])->nullable()->change();
        });
    }
}
