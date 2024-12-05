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
        Schema::create('suspect_to_offenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('suspect_id');
            $table->foreign('suspect_id')->references('id')->on('suspects')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('offense_id');
            $table->foreign('offense_id')->references('id')->on('offenses')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suspect_to_offenses');
    }
};
