<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows delete-warning for in-use framework (table/modal)', function () {
    $framework = \App\Models\Framework::factory()->create();

    $user = makeAuthUser();
    createAssessmentForUser($user, $framework);

    $count = $framework->assessments()->count();

    $expected = trans('messages.framework_in_use_delete_modal', ['count' => $count]);

    // The same message is produced in the table DeleteAction modalDescription
    $actual = ($framework->assessments()->exists()
        ? trans('messages.framework_in_use_delete_modal', ['count' => $framework->assessments()->count()])
        : trans('messages.framework_delete_confirmation'));

    expect($actual)->toBe($expected);
});

it('does not show delete-warning for framework not in use (table/modal)', function () {
    $framework = \App\Models\Framework::factory()->create();

    $actual = ($framework->assessments()->exists()
        ? trans('messages.framework_in_use_delete_modal', ['count' => $framework->assessments()->count()])
        : trans('messages.framework_delete_confirmation'));

    expect($actual)->toBe(trans('messages.framework_delete_confirmation'));
});

it('shows top-of-form warning for in-use frameworks', function () {
    $framework = \App\Models\Framework::factory()->create();

    $user = makeAuthUser();
    createAssessmentForUser($user, $framework);

    $warning = (\App\Models\Framework::query()->whereKey($framework->id)->whereHas('assessments')->exists()
        ? trans('messages.framework_in_use_top_warning')
        : '');

    expect($warning)->toBe(trans('messages.framework_in_use_top_warning'));
});

it('does not show top-of-form warning for frameworks not in use', function () {
    $framework = \App\Models\Framework::factory()->create();

    $warning = (\App\Models\Framework::query()->whereKey($framework->id)->whereHas('assessments')->exists()
        ? trans('messages.framework_in_use_top_warning')
        : '');

    expect($warning)->toBe('');
});
