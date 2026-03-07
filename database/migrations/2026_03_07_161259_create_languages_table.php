<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code', 5)->unique(); // sr, en, de
            $table->timestamps();
        });

        // Populate initial languages
        DB::table('languages')->insert([
            ['name' => 'Srpski', 'code' => 'sr', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Engleski', 'code' => 'en', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Njemački', 'code' => 'de', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
