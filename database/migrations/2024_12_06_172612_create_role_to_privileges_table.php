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
        Schema::create('role_to_privileges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id'); // Foreign Key to roles table
            $table->unsignedBigInteger('privilege_id'); // Foreign Key to permissions table

            // Define foreign keys
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('privilege_id')->references('id')->on('privileges')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_to_privileges');
    }
};
