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
        Schema::table('operations', function (Blueprint $table) {
            // Add the foreign key column
            $table->unsignedBigInteger('route_id')->nullable();

            // Add the foreign key constraint
            $table->foreign('route_id')
                ->references('id')
                ->on('routes')
                ->onDelete('cascade') // Optional: delete operation if the route is deleted
                ->onUpdate('cascade'); // Optional: update routes_id on routes.id change
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
             // Drop the foreign key constraint first
            $table->dropForeign(['route_id']);

             // Then drop the column
            $table->dropColumn('route_id');

        });
    }
};
