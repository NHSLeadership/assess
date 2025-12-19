<?php

use App\Models\AssessmentVariantSelection;

test('assessment variant selection has fillable attributes', function () {
    $pivot = new AssessmentVariantSelection([
        'assessment_id'                 => 10,
        'framework_variant_attribute_id'=> 20,
        'framework_variant_option_id'   => 30,
    ]);

    expect($pivot->assessment_id)->toEqual(10)
        ->and($pivot->framework_variant_attribute_id)->toEqual(20)
        ->and($pivot->framework_variant_option_id)->toEqual(30);
});

