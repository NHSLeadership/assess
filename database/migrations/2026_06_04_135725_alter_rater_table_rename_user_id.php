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
        Schema::table('raters', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropUnique(['user_id']);
            $table->renameColumn('user_id', 'subject_id');
            $table->index(['subject_id']);
            $table->unique(['subject_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('raters', function (Blueprint $table) {
            $table->dropUnique(['subject_id', 'email']);
            $table->dropIndex(['subject_id']);
            $table->renameColumn('subject_id', 'user_id');
            $table->index(['user_id']);
            $table->unique(['user_id']);
        });
    }
};
