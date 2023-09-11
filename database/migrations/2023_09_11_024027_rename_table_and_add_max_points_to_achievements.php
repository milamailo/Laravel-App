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
        // Rename the table from achievemenets to achievements
        Schema::rename('achievemenets', 'achievements');

        // Add the max_points field to the achievements table
        Schema::table('achievements', function (Blueprint $table) {
            $table->integer('max_points')->nullable()->after('min_points');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove the max_points field from the achievements table
        Schema::table('achievements', function (Blueprint $table) {
            $table->dropColumn('max_points');
        });

        // Rename the table back to achievemenets
        Schema::rename('achievements', 'achievemenets');
    }
};
