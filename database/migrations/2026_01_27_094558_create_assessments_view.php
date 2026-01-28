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
            CREATE OR REPLACE VIEW assessments_view AS
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS assessments_view');
    }
};
