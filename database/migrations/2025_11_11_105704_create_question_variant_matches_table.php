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
        Schema::create('question_variant_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_variant_id')
                ->constrained('question_variants')
                ->cascadeOnDelete();
            $table->foreignId('framework_variant_attribute_id')
                ->constrained('framework_variant_attributes')
                ->cascadeOnDelete();
            $table->foreignId('framework_variant_option_id')
                ->constrained('framework_variant_options')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['question_variant_id', 'framework_variant_attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_variant_matches');
    }
};
