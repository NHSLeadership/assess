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
        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->renameColumn('role', 'type');
            $table->string('type', 10)->default('self')->change();
            $table->dropColumn('is_self');
            $table->foreignId('rater_group_id')->nullable()->constrained();
            $table->dropIndex(['assessment_id','is_self']);
            $table->dropIndex(['assessment_id','role']);
            $table->index('assessment_id');
            $table->index(['assessment_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->renameColumn('type', 'role');
            $table->enum('role', ['self', 'manager', 'report', 'peer', 'other'])->default('self')->change();
            $table->boolean('is_self')->default(true);
            $table->dropConstrainedForeignId('rater_group_id');
            $table->index(['assessment_id', 'is_self']);
            $table->index(['assessment_id', 'role']);
            $table->dropIndex(['assessment_id']);
            $table->dropIndex(['assessment_id', 'type']);
        });
    }
};
