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
        Schema::table('programmes', function (Blueprint $table) {
            //
            Schema::table('programmes', function (Blueprint $table) {
                // Drop the column if it exists
                $table->date('expirationDate')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            //
            Schema::table('programmes', function (Blueprint $table) {
                // Drop the column if it exists
                $table->dropColumn('expirationDate');
            });
        });
    }
};
