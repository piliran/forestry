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
        Schema::create('funder_to_operations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operation_id');
            $table->foreign('operation_id')->references('id')->on('operations')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('funded_by');
            $table->foreign('funded_by')->references('id')->on('funders')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funder_to_operations');
    }
};
