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
        Schema::create('arrests', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->date('date');
            $table->string('location');
            $table->string('proof');
            $table->unsignedBigInteger('suspect_id');
            $table->foreign('suspect_id')->references('id')->on('suspects')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('confiscate_id');
            $table->foreign('confiscate_id')->references('id')->on('confiscates')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arrests');
    }
};
