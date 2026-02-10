<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\MediaType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table('media_types')->insert([
            ['name' => MediaType::SONG->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => MediaType::SHOW->value, 'created_at' => now(), 'updated_at' => now()],
            ['name' => MediaType::PODCAST->value, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_types');
    }
};
