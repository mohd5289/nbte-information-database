<?php

use App\Models\IEI;
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
        Schema::create('i_e_i_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(IEI::class)->onDelete('cascade');
            $table->string('name');
            $table->integer('yearGrantedInterimOrAccredition');
            $table->integer('yearApproved');
            $table->string('accreditationStatus');
            $table->integer('approvedStream');
            $table->date('expirationDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('i_e_i_programmes');
    }
};
