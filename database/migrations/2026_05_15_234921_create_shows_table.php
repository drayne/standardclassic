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
        Schema::create('shows', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('shows')->insert([
            [
                'name' => 'Popodne sa Acom Informacijom',
                'slug' => 'popodne-sa-acom-informacijom',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vijesti sa Dankom - iz zemlje i svijeta',
                'slug' => 'vijesti-sa-dankom-iz-zemlje-i-svijeta',
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
        Schema::dropIfExists('shows');
    }
};
