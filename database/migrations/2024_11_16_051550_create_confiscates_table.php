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
        Schema::create('confiscates', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('quantity');       
            $table->unsignedBigInteger('suspect_id');
            $table->foreign('suspect_id')->references('id')->on('suspects')->onDelete('cascade')->onUpdate('cascade');
           
            $table->string('proof')->nullable();
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('confiscates');
    }
};
