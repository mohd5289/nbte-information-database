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
        Schema::table('v_e_i_programmes', function (Blueprint $table) {
            //
            $table->renameColumn('yearGrantedInterimOrAccredition', 'yearGrantedInterimOrAccreditation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('v_e_i_programmes', function (Blueprint $table) {
            //
            $table->renameColumn('yearGrantedInterimOrAccreditation', 'yearGrantedInterimOrAccredition');
        });
    }
};