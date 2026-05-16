<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('podcasts')->insert([
            [
                'name' => 'Gdje se fura (ne)kultura',
                'slug' => 'gdje-se-fura-ne-kultura',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Miloš Stevanović Standard Podkast',
                'slug' => 'milos-stevanovic-standard-podkast',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
