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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('rater_id')
                ->constrained('raters')
                ->cascadeOnDelete();
            $table->foreignId('question_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('scale_option_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->smallInteger('numeric_value')->nullable();
            $table->text('free_text')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();
            $table->unique(
                ['assessment_id', 'question_id', 'rater_id'],
                'responses_assessment_question_rater_unique'
            );
            $table->index(['assessment_id', 'rater_id'], 'responses_assessment_rater_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
