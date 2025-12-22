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
        Schema::table('framework_variant_attributes', function (Blueprint $table) {
            $table->string('hint_text')->nullable()->after('label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('framework_variant_attributes', function (Blueprint $table) {
            $table->dropColumn('hint_text');
        });
    }
};
