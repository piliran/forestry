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
        Schema::create('encroacheds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade')->onUpdate('cascade');
            $table->string('encroachment_type');
            $table->string('estimated_area');
            $table->string('remarks');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('encroacheds');
    }
};
