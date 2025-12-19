<?php

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\User;
use App\Models\FrameworkVariantAttribute;
use App\Models\FrameworkVariantOption;
use App\Models\AssessmentVariantSelection;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->make(['user_id' => '1000000000']);
    $this->framework = Framework::factory()->create();
    $this->assessment = Assessment::factory()->create([
        'framework_id' => $this->framework->id,
        'user_id'      => $this->user->id,
    ]);
    $this->attribute = FrameworkVariantAttribute::factory()->create(
        ['framework_id' => $this->framework->id]
    );
    $this->option    = FrameworkVariantOption::factory()->create([
        'framework_variant_attribute_id' => $this->attribute->id,
    ]);
});

test('assessment variant selection persists correctly', function () {
    $pivot = AssessmentVariantSelection::create([
        'assessment_id'                 => $this->assessment->id,
        'framework_variant_attribute_id'=> $this->attribute->id,
        'framework_variant_option_id'   => $this->option->id,
    ]);

    expect($pivot->exists)->toBeTrue()
        ->and($pivot->assessment_id)->toEqual($this->assessment->id)
        ->and($pivot->framework_variant_attribute_id)->toEqual($this->attribute->id)
        ->and($pivot->framework_variant_option_id)->toEqual($this->option->id);
});

test('assessment variant selection belongs to assessment, attribute, and option', function () {
    $pivot = AssessmentVariantSelection::create([
        'assessment_id'                 => $this->assessment->id,
        'framework_variant_attribute_id'=> $this->attribute->id,
        'framework_variant_option_id'   => $this->option->id,
    ]);

    expect($pivot->assessment->id)->toEqual($this->assessment->id)
        ->and($pivot->attribute->id)->toEqual($this->attribute->id)
        ->and($pivot->option->id)->toEqual($this->option->id);
});
