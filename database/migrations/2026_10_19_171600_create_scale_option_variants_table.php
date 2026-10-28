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
        Schema::create('scale_option_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scale_option_id')->constrained('scale_options')->cascadeOnDelete();
            $table->text('text');
            $table->string('audience', 16)->nullable();
            $table->integer('priority')->default(0);
            $table->timestamps();
            $table->index(['scale_option_id', 'audience', 'priority'], 'sov_scale_option_audience_priority_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scale_option_variants');
    }
};

