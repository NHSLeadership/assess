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
        Schema::create('scale_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scale_id')->constrained()->cascadeOnDelete();
            $table->string('label');
            $table->integer('value');
            $table->unsignedInteger('order')->default(0);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['scale_id', 'value']);
            $table->index(['scale_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scale_options');
    }
};
