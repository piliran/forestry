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
        Schema::create('operations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('station_id');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->string('description');
            $table->date('date_of_operation');
            $table->timestamp('date_time_of_deployment');
            $table->timestamp('date_time_of_withdrawal');
            $table->string('type_of_patrol');
            $table->string('funded_by');
            $table->foreign('station_id')->references('id')->on('stations')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('route_id');
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('team_leader');
            $table->foreign('team_leader')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('operation_type_id');
            $table->foreign('operation_type_id')->references('id')->on('operation_types')->onDelete('cascade')->onUpdate('cascade');
        
            $table->softDeletes(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operations');
    }
};
