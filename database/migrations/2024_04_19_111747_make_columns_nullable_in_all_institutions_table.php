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
        Schema::table('all_institutions', function (Blueprint $table) {
            //
            Schema::table('all_institutions', function (Blueprint $table) {
                $table->string('address')->nullable()->change();
                $table->enum('ownership', ['Federal', 'State', 'Private'])->nullable()->change();
                $table->unsignedSmallInteger('year_established')->nullable()->change();
               
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_institutions', function (Blueprint $table) {
            //
        });
    }
};
