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
        Schema::table('question_variants', function (Blueprint $table) {
            $table->renameColumn('rater_type', 'audience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_variants', function (Blueprint $table) {
            $table->renameColumn('audience', 'rater_type');
        });
    }
};
