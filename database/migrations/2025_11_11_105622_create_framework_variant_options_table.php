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
        Schema::create('framework_variant_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_variant_attribute_id')
                ->constrained('framework_variant_attributes')
                ->cascadeOnDelete();
            $table->string('value');
            $table->string('label');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->unique(['framework_variant_attribute_id', 'value'], 'fvo_fva_id_value_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('framework_variant_options');
    }
};
