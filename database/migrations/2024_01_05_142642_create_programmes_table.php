<?php

use App\Models\Institution;
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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Institution::class)->onDelete('cascade');
            $table->string('name');
            $table->boolean('isTechnologyBased');
            $table->integer('yearGrantedInterimOrAccreditation');
            $table->string('accreditationStatus');
            $table->integer('approvedStream');
            $table->string('faculty');
            $table->date('expirationDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
// $table->foreignIdFor(Institution::class)->onDelete('cascade');
// $table->string('name');
// $table->integer('yearGrantedInterimOrAccredition');
// $table->integer('yearApproved');
// $table->string('accreditationStatus');
// $table->integer('approvedStream');
// $table->date('expirationDate');