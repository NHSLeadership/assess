<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function dropViews(): void
    {
        DB::statement('DROP VIEW IF EXISTS responses_view');
        DB::statement('DROP VIEW IF EXISTS assessments_view');
    }

    private function createViews(): void
    {
        DB::statement('
            CREATE VIEW assessments_view AS
                SELECT
                    a.id AS assessment_id,
                    f.name AS framework_name,
                    a.user_id,
                    fva.key AS attribute_key,
                    fvo.value AS option_value,
                    a.created_at,
                    a.submitted_at
                FROM
                    assessments a
                    JOIN frameworks f ON f.id = a.framework_id
                    LEFT JOIN assessment_variant_selections avs ON avs.assessment_id = a.id
                    LEFT JOIN framework_variant_attributes fva ON fva.id = avs.framework_variant_attribute_id
                    LEFT JOIN framework_variant_options fvo ON fvo.id = avs.framework_variant_option_id
                ORDER BY
                    a.id DESC;
        ');

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
     * Run the migrations.
     */
    public function up(): void
    {
        $this->dropViews();

        Schema::table('nodes', function (Blueprint $table) {
            $table->integer('parent_id_new')->after('parent_id')->default(-1);
        });

        DB::statement('UPDATE nodes SET parent_id_new = COALESCE(parent_id, -1);');

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropForeign(['framework_id']);
            $table->renameColumn('parent_id', 'parent_id_old');
            $table->renameColumn('parent_id_new', 'parent_id');
            $table->dropIndex('nodes_framework_id_parent_id_order_index');
            $table->index(['framework_id', 'parent_id', 'order'], 'nodes_framework_id_parent_id_order_index');
            $table->dropColumn('parent_id_old');
        });

        $this->createViews();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->dropViews();

        Schema::table('nodes', function (Blueprint $table) {
            $table->integer('parent_id_old')->nullable()->after('parent_id');
        });

        DB::statement('
        UPDATE nodes
        SET parent_id_old = CASE
            WHEN parent_id = -1 THEN NULL
            ELSE parent_id
        END
    ');

        Schema::table('nodes', function (Blueprint $table) {
            $table->dropIndex('nodes_framework_id_parent_id_order_index');
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->renameColumn('parent_id', 'parent_id_new');
            $table->renameColumn('parent_id_old', 'parent_id');
            $table->dropColumn('parent_id_new');
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->index(
                ['framework_id', 'parent_id', 'order'],
                'nodes_framework_id_parent_id_order_index'
            );
        });

        Schema::table('nodes', function (Blueprint $table) {
            $table->foreign('parent_id')
                ->references('id')
                ->on('nodes')
                ->nullOnDelete();

            $table->foreign('framework_id')
                ->references('id')
                ->on('frameworks')
                ->cascadeOnDelete();
        });

        $this->createViews();
    }
};
