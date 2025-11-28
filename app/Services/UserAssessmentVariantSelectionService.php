<?php

namespace App\Services;

use App\Models\AssessmentVariantSelection;
use App\Models\FrameworkVariantAttribute;

use Illuminate\Support\Facades\Log;

class UserAssessmentVariantSelectionService
{
    public static function updateOrCreate(mixed $value, FrameworkVariantAttribute $attribute, int $assessmentId, int $raterId = null): void
    {

        if (isset($attribute->id)) {
            try {
                AssessmentVariantSelection::updateOrCreate([
                    'assessment_id' => $assessmentId,
                    'framework_variant_attribute_id' => $attribute->id,
                ], [
                    'framework_variant_option_id' => $value,
                    'updated_at' => now(),
                ]);
            } catch (\Throwable $e) {
                Log::error('Error saving form field {framework_variant_attribute_id}. {details}', [
                    'framework_variant_attribute_id' => $attribute->id,
                    'details' => $e->getMessage()
                ]);
            }
        }
    }
}
