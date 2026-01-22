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
        Schema::table('frameworks', function (Blueprint $table) {
            $table->text('report_html')->nullable()->after('report_intro');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('frameworks', function (Blueprint $table) {
            $table->dropColumn('report_html');
        });
    }
};