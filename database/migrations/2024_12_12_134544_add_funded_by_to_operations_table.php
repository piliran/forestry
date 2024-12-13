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
            $table->unsignedBigInteger('funded_by')->nullable();

            // Add the foreign key constraint
            $table->foreign('funded_by')
                ->references('id')
                ->on('funders')
                ->onDelete('cascade') // Optional: delete articles if the category is deleted
                ->onUpdate('cascade'); // Optional: update category_id on categories.id change


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operations', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['funded_by']);

            // Then drop the column
            $table->dropColumn('funded_by');
        });
    }
};
