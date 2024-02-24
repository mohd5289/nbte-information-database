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
        Schema::table('programmes_again', function (Blueprint $table) {
            $table->integer('yearApproved')->before('yearGrantedInterimOrAccreditation')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programmes_again', function (Blueprint $table) {
            //
        });
    }
};
