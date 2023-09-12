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
        Schema::table('user_achievements_badges', function (Blueprint $table) {
            $table->renameColumn('Lesson_level', 'lesson_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_achievements_badges', function (Blueprint $table) {
            $table->renameColumn('lesson_level', 'Lesson_level');
        });
    }
};
