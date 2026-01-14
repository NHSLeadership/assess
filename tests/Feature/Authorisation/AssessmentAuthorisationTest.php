<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    // A test user that mocks Auth0 permissions
    $this->user = new class extends \App\Models\User {
        public array $fakePermissions = [];

        public function can($ability, $arguments = [])
        {
            if (! empty($this->fakePermissions)) {
                return collect($this->fakePermissions)
                    ->pluck('permission_name')
                    ->contains($ability);
            }

            return parent::can($ability, $arguments);
        }
    };

    // Create framework + assessment
    $this->framework = \App\Models\Framework::factory()->create();

    $this->assessment = \App\Models\Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $this->framework->id,
    ]);
});

test('denies update when user lacks permission', function () {
    $this->user->fakePermissions = [];

    expect(\Illuminate\Support\Facades\Gate::forUser($this->user)->allows('update', $this->assessment))
        ->toBeFalse();
});

test('allows update when user has assessment:update permission', function () {
    $this->user->fakePermissions = [
        ['permission_name' => 'assessment:update'],
    ];

    expect(\Illuminate\Support\Facades\Gate::forUser($this->user)->allows('update', $this->assessment))
        ->toBeTrue();
});
