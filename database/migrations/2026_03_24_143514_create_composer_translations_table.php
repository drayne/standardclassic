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
        Schema::create('composer_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('composer_id')->constrained()->onDelete('cascade');
            $table->foreignId('language_id')->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['composer_id', 'language_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('composer_translations');
    }
};
