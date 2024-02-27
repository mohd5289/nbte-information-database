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
        Schema::table('allprogrammes', function (Blueprint $table) {
            // First, drop the existing boolean column
            $table->dropColumn('isTechnologyBased');
            
            // Then, add a new string column with a default value of null
            $table->string('isTechnologyBased')->nullable()->default("")->after('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('allprogrammes', function (Blueprint $table) {
            // Drop the new string column
            $table->dropColumn('isTechnologyBased');
            
            // Recreate the boolean column if needed
            $table->boolean('isTechnologyBased')->nullable()->default(null)->after('name');
        });
    }
};
