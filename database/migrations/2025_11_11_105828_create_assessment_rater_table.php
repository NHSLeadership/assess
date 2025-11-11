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
        Schema::create('assessment_rater', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rater_id')->constrained('raters')->cascadeOnDelete();
            $table->enum('role', ['self', 'manager', 'direct_report', 'peer', 'other'])->default('self');
            $table->boolean('is_self')->default(true);
            $table->timestamps();
            $table->unique(['assessment_id', 'rater_id']);
            $table->index(['assessment_id', 'role']);
            $table->index(['assessment_id', 'is_self']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_rater');
    }
};
