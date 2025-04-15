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
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->enum('title_size', ['h1', 'h2', 'h3', 'h4', 'h5'])->default('h1');
            $table->enum('title_case', ['uppercase', 'capitalize', 'lowercase'])->default('uppercase');
            $table->string('title_color')->default('#ffffff');

            $table->string('subtitle')->nullable();
            $table->enum('subtitle_size', ['h1', 'h2', 'h3', 'h4', 'h5'])->nullable();
            $table->enum('subtitle_case', ['uppercase', 'capitalize', 'lowercase'])->nullable();
            $table->string('subtitle_color')->default('#ffffff');

            $table->string('url_text')->nullable();
            $table->string('url')->nullable();

            $table->string('content_alignment')->default('left');
            $table->boolean('slider_status');
            $table->unsignedSmallInteger('order')->default(0);

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
