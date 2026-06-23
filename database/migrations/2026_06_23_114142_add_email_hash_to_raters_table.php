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
        DB::statement('DROP VIEW IF EXISTS responses_view');

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

        DB::statement('
            CREATE VIEW responses_view AS
                SELECT
                    r.assessment_id,
                    r.id AS response_id,
                    rt.subject_id AS rater_user_id,
                    ar.type AS rater_role,
                    nt.name AS node_type,
                    n.name AS node_name,
                    q.id AS question_id,
                    q.title AS question_title,
                    so.value AS score,
                    so.label AS scale_label,
                    r.created_at AS response_created_at
                FROM
                    responses r
                    JOIN assessments a ON a.id = r.assessment_id
                    JOIN questions q ON q.id = r.question_id
                    JOIN nodes n ON n.id = q.node_id
                    JOIN node_types nt ON nt.id = n.node_type_id
                    LEFT JOIN scale_options so ON so.id = r.scale_option_id
                    LEFT JOIN assessment_rater ar ON ar.assessment_id = r.assessment_id
                    AND ar.rater_id = r.rater_id
                    LEFT JOIN raters rt ON rt.id = ar.rater_id
                WHERE
                    q.response_type = "scale"
                ORDER BY
                    r.assessment_id,
                    ar.type,
                    n.`order`,
                    q.id;
        ');
    }

    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS responses_view');

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

        DB::statement('
            CREATE VIEW responses_view AS
                SELECT
                    r.assessment_id,
                    r.id AS response_id,
                    rt.subject_id AS rater_user_id,
                    ar.type AS rater_role,
                    nt.name AS node_type,
                    n.name AS node_name,
                    q.id AS question_id,
                    q.title AS question_title,
                    so.value AS score,
                    so.label AS scale_label,
                    r.created_at AS response_created_at
                FROM
                    responses r
                    JOIN assessments a ON a.id = r.assessment_id
                    JOIN questions q ON q.id = r.question_id
                    JOIN nodes n ON n.id = q.node_id
                    JOIN node_types nt ON nt.id = n.node_type_id
                    LEFT JOIN scale_options so ON so.id = r.scale_option_id
                    LEFT JOIN assessment_rater ar ON ar.assessment_id = r.assessment_id
                    AND ar.rater_id = r.rater_id
                    LEFT JOIN raters rt ON rt.id = ar.rater_id
                WHERE
                    q.response_type = "scale"
                ORDER BY
                    r.assessment_id,
                    ar.type,
                    n.`order`,
                    q.id;
        ');
    }
};
