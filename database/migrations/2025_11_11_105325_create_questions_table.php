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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')
                ->constrained('nodes')
                ->cascadeOnDelete();
            $table->string('title');
            $table->text('text');
            $table->string('hint')->nullable();
            $table->string('placeholder')->nullable();
            $table->enum('response_type', [
                'single_choice',
                'multi_choice',
                'scale',
                'boolean',
                'free_text',
            ])->default('scale');
            $table->foreignId('scale_id')
                ->nullable()
                ->constrained('scales')
                ->nullOnDelete();
            $table->boolean('required')->default(true);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->index(['node_id', 'order']);
            $table->index('scale_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
