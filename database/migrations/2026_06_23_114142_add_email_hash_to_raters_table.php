<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raters', function (Blueprint $table) {
            $table->dropUnique([
                'subject_id',
                'email',
            ]);
            $table->dropIndex(['email']);
        });

        Schema::table('raters', function (Blueprint $table) {
            // Change strings to text since encrypted values can exceed 255 chars
            $table->text('name')->nullable()->change();
            $table->text('email')->nullable()->change();

            $table->string('email_hash', 64)
                ->nullable()
                ->after('email');
        });

        // Encrypt names and emails and add email hashes
        DB::table('raters')
            ->orderBy('id')
            ->get()
            ->each(function ($rater): void {
                $email = $rater->email !== null
                    ? strtolower(trim($rater->email))
                    : null;

                DB::table('raters')
                    ->where('id', $rater->id)
                    ->update([
                        'name' => $rater->name !== null
                            ? Crypt::encryptString($rater->name)
                            : null,

                        'email' => $email !== null
                            ? Crypt::encryptString($email)
                            : null,

                        'email_hash' => $email !== null
                            ? hash_hmac('sha256', $email, config('app.key'))
                            : null,
                    ]);
            });

        Schema::table('raters', function (Blueprint $table) {
            $table->unique([
                'subject_id',
                'email_hash',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('raters', function (Blueprint $table) {
            $table->dropUnique([
                'subject_id',
                'email_hash',
            ]);
        });

        // Decrypt names and emails
        DB::table('raters')
            ->orderBy('id')
            ->get()
            ->each(function ($rater): void {
                DB::table('raters')
                    ->where('id', $rater->id)
                    ->update([
                        'name' => $rater->name !== null
                            ? Crypt::decryptString($rater->name)
                            : null,

                        'email' => $rater->email !== null
                            ? Crypt::decryptString($rater->email)
                            : null,
                    ]);
            });

        Schema::table('raters', function (Blueprint $table) {
            $table->dropColumn('email_hash');

            // Switch back to strings
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();

            $table->unique([
                'subject_id',
                'email',
            ]);
            $table->index('email');
        });
    }
};
