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
        Schema::create('signposts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('node_id')->constrained()->cascadeOnDelete();
            $table->foreignId('framework_variant_option_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('min_value');
            $table->unsignedInteger('max_value');
            $table->text('guidance');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('signposts');
    }
};
