<?php

use App\Models\VEI;
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
        if (!Schema::hasTable('v_e_i_programmes')) {
        Schema::create('v_e_i_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VEI::class)->onDelete('cascade');
            $table->string('name');
            $table->integer('yearGrantedInterimOrAccredition');
            $table->integer('yearApproved');
            $table->string('accreditationStatus');
            $table->integer('approvedStream');
            $table->date('expirationDate');
            $table->timestamps();
        });
    }
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('v_e_i_programmes');
    }
};
