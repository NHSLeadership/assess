<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->string('type', 10)
                ->nullable(false)
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('assessment_rater', function (Blueprint $table) {
            $table->string('type', 10)
                ->default('self')
                ->nullable(false)
                ->change();
        });
    }
};
