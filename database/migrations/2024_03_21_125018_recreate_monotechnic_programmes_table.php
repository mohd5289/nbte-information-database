<?php

use App\Models\MonotechnicInstitution;
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
        //
        Schema::dropIfExists('monotechnic_programmes'); // Drop the existing table if it exists

        Schema::create('monotechnic_programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(MonotechnicInstitution::class)->onDelete('cascade');
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
        //
        Schema::dropIfExists('monotechnic_programmes');
    }
};
