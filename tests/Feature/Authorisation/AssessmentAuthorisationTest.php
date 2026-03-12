<?php

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

beforeEach(function () {
    // A test user that mocks Auth0 permissions
    $this->user = new class extends User
    {
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
    $this->framework = Framework::factory()->create();

    $this->assessment = Assessment::factory()->create([
        'user_id' => 1,
        'framework_id' => $this->framework->id,
    ]);
});

test('denies update when user lacks permission', function () {
    $this->user->fakePermissions = [];

    expect(Gate::forUser($this->user)->allows('update', $this->assessment))
        ->toBeFalse();
});

test('allows update when user has assessment:update permission', function () {
    $this->user->fakePermissions = [
        ['permission_name' => 'assessment:update'],
    ];

    expect(Gate::forUser($this->user)->allows('update', $this->assessment))
        ->toBeTrue();
});
