<?php

use App\Models\Framework;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('framework can be created with factory', function () {
    $framework = Framework::factory()->create([
        'slug'        => 'leadership-framework',
        'description' => 'Framework for leadership skills',
        'instructions'=> 'Answer all questions honestly',
        'report_intro'=> 'This report summarises your leadership skills',
    ]);

    expect($framework->exists)->toBeTrue()
        ->and($framework->slug)->toEqual('leadership-framework');
});

test('framework supports soft deletes', function () {
    $framework = Framework::factory()->create();

    $framework->delete();

    expect($framework->trashed())->toBeTrue();
    expect(Framework::withTrashed()->find($framework->id))->not->toBeNull();
});

test('framework can be restored after soft delete', function () {
    $framework = Framework::factory()->create();

    $framework->delete();
    expect($framework->trashed())->toBeTrue();

    $framework->restore();
    expect($framework->trashed())->toBeFalse();
});
