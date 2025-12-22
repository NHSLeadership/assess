<?php

beforeEach(function () {
    // A test user that mocks Auth0 permissions
    $this->user = new class extends \App\Models\User {
        public array $fakePermissions = [];

        public function getAuth0Permissions(): array
        {
            return $this->fakePermissions;
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
