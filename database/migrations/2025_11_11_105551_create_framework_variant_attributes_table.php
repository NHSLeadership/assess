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
        Schema::create('framework_variant_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained('frameworks')->cascadeOnDelete();
            $table->string('key');
            $table->string('label');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->unique(['framework_id', 'key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('framework_variant_attributes');
    }
};
