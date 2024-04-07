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
            $table->string('address');
            $table->enum('ownership', ['Federal', 'State', 'Private']);
            $table->unsignedSmallInteger('year_established');
            $table->string('rector_name')->nullable();
            $table->string('rector_email')->nullable();
            $table->string('rector_phone')->nullable();
            $table->string('proprietor_name')->nullable();
            $table->string('proprietor_email')->nullable();
            $table->string('proprietor_phone')->nullable();
            $table->string('registrar_name')->nullable();
            $table->string('registrar_email')->nullable();
            $table->string('registrar_phone')->nullable();   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('all_institutions', function (Blueprint $table) {
            //
            $table->dropColumn('address');
            $table->dropColumn('ownership');
            $table->dropColumn('year_established');
            $table->dropColumn('rector_name');
            $table->dropColumn('rector_email');
            $table->dropColumn('rector_phone');
            $table->dropColumn('proprietor_name');
            $table->dropColumn('proprietor_email');
            $table->dropColumn('proprietor_phone');
            $table->dropColumn('registrar_name');
            $table->dropColumn('registrar_email');
            $table->dropColumn('registrar_phone');
        });
    }
};
