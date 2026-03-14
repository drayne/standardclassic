<?php

use App\Enums\Category;
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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        DB::table('categories')->insert([
            ['name'       => 'Vijesti iz kulture',
             'slug'       => Category::KULTURA->value,
             'created_at' => now(),
             'updated_at' => now()
            ],
            [
                'name'       => 'Vijesti iz dnevno-političkog života',
                'slug'       => Category::DNEVNOPOLITICKE->value,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
