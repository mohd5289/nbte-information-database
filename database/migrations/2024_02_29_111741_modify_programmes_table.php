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
            $table->dropColumn(['faculty', 'isTechnologyBased', 'expirationDate']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmes', function (Blueprint $table) {
            //
            $table->string('faculty');
            $table->boolean('isTechnologyBased');
            $table->date('expirationDate');
        });
    }
};
