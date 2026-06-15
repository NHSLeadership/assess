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
        // Drop indexes that reference the old columns before changing them
        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->dropIndex(['assessment_id', 'is_self']);
            $table->dropIndex(['assessment_id', 'role']);
        });

        Schema::table('assessment_rater', function (Blueprint $table) {
            // Rename the enum column to the new name and adjust its definition
            $table->renameColumn('role', 'type');
            $table->string('type', 10)->default('self')->change();

            // Remove the obsolete column and add the new foreign id
            $table->dropColumn('is_self');
            $table->foreignId('rater_group_id')->nullable()->constrained();

            // Recreate the necessary indexes on the new structure
            $table->index('assessment_id');
            $table->index(['assessment_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes that reference the new columns before reverting
        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->dropIndex(['assessment_id', 'type']);
            $table->dropIndex(['assessment_id']);
        });

        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->renameColumn('type', 'role');
            $table->enum('role', ['self', 'manager', 'direct_report', 'peer', 'other'])->default('self')->change();
            $table->boolean('is_self')->default(true);
            $table->dropConstrainedForeignId('rater_group_id');

            $table->index(['assessment_id', 'is_self']);
            $table->index(['assessment_id', 'role']);
        });
    }
};
