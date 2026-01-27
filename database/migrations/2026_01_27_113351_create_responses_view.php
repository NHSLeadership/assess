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
        DB::statement('
            CREATE VIEW responses_view AS
                SELECT
                    r.assessment_id,
                    r.id AS response_id,
                    rt.user_id AS rater_user_id,
                    ar.role AS rater_role,
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
                    ar.role,
                    n.`order`,
                    q.id;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses_view');
    }
};
