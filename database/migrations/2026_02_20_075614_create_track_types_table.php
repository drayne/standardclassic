<?php

use App\Enums\TrackType;
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
        Schema::create('track_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
        });

        DB::table('track_types')->insert([
            ['name' => TrackType::REGULAR],
            ['name' => TrackType::SCHEDULED],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('track_types');
    }
};
