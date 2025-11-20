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
        Schema::create('assessment_variant_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('framework_variant_attribute_id')
                ->constrained('framework_variant_attributes', null, 'avs_fva_id_foreign')
                ->cascadeOnDelete();
            $table->foreignId('framework_variant_option_id')
                ->constrained('framework_variant_options', null, 'avs_fvo_id_foreign')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['assessment_id', 'framework_variant_attribute_id'], 'assessment_variant_unique_attr');
            $table->index(['assessment_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_variant_selections');
    }
};
