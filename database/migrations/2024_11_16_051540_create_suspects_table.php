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
        Schema::create('suspects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('age')->nullable();
            $table->string('sex');
            $table->string('national_id');
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('operation_team_id');
            $table->foreign('operation_team_id')->references('id')->on('operation_to_teams')->onDelete('cascade')->onUpdate('cascade');
            $table->string('village');
            $table->string('TA');
            $table->string('suspect_photo_path', 2048)->nullable();   
            $table->enum('status', [
                'Under Investigation',  // Default: suspect has been registered and is under investigation
                'Arrested',             // Suspect is taken into custody
                'Released Without Charge', // Released by police without charges
                'Court Hearing Pending',   // Awaiting court proceedings
                'Acquitted',            // Found not guilty and released by the court
                'Convicted'             // Found guilty and convicted in court
            ])->default('Under Investigation');       
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspects');
    }
};
