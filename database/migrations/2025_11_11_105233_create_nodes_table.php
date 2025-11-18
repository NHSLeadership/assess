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
        Schema::create('nodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('framework_id')->constrained()->cascadeOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('nodes')->cascadeOnDelete();
            $table->foreignId('node_type_id')->constrained();
            $table->enum('colour', ['blue', 'green', 'grey', 'aqua-green', 'orange', 'purple', 'pink', 'red', 'white', 'yellow'])->default('blue');
            $table->string('name');
            $table->text('description');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
            $table->index(['framework_id', 'parent_id', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nodes');
    }
};
