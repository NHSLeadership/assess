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

        $signposts = DB::table('signposts')->get();

        foreach ($signposts as $sp) {
            $updated = preg_replace(
                '/\s*(target="_blank"|rel="noopener")/i',
                '',
                $sp->guidance
            );

            DB::table('signposts')
                ->where('id', $sp->id)
                ->update([
                    'guidance' => $updated,
                ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Not reversible
    }
};
