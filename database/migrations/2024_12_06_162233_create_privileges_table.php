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


        Schema::create('privileges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('table_to_permission_id');
            $table->string('privilege');


            // Foreign key constraint
            $table->foreign('table_to_permission_id')->references('id')->on('table_to_permissions')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('privileges');
    }
};
