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
        Schema::create('scale_option_variant_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scale_option_variant_id')
                ->constrained('scale_option_variants', null, 'sovm_sov_id_foreign')
                ->cascadeOnDelete();
            $table->foreignId('framework_variant_attribute_id')
                ->constrained('framework_variant_attributes', null, 'sovm_fva_id_foreign')
                ->cascadeOnDelete();
            $table->foreignId('framework_variant_option_id')
                ->constrained('framework_variant_options', null, 'sovm_fvo_id_foreign')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['scale_option_variant_id', 'framework_variant_attribute_id'], 'sovm_sov_fva_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scale_option_variant_matches');
    }
};

