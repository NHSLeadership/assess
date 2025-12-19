<?php

use App\Models\User;
use Livewire\Livewire;

test('login screen redirects to Auth0', function () {
    $response = $this->get('/login');

    // Auth0 usually redirects to its hosted login page
    $response->assertStatus(302);
    $response->assertRedirectContains('leadershipacademy.nhs.uk');
});

test('admin users can authenticate via Auth0', function () {
    $user = new User([
        'id' => 'auth0|123456',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'user_id' => 'TestUser',
    ]);


    Livewire::actingAs($user);

    $response = $this->get('/admin');

    $response->assertStatus(200);
    $this->assertAuthenticated();
});



test('users can logout', function () {
    $user = new User([
        'id' => 'auth0|123456',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'user_id' => 'TestUser',
    ]);

    $this->actingAs($user);

    $response = $this->get('/logout');   // <-- GET instead of POST

    $response->assertRedirectContains('leadershipacademy.nhs.uk/v2/logout');

    $this->assertGuest();
});
