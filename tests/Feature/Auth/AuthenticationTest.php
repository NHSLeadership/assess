<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Livewire;

test('login screen redirects to Auth0', function () {
    $response = $this->get('/login');

    // Auth0 usually redirects to its hosted login page
    $response->assertStatus(302);
    $response->assertRedirectContains('leadershipacademy.nhs.uk');
});

test('unauthenticated users cannot access admin panel', function () {
    $response = $this->get('/admin');

    $response->assertRedirect(); // usually to login or home
});

test('unauthorized users receive 403 when accessing admin panel', function () {
    $user = new User([
        'user_id' => 'auth0|123456',
        'email' => 'test@example.com',
    ]);

    Livewire::actingAs($user, 'auth0-session');

    $response = $this->get('/admin');

    $response->assertStatus(403);
});

test('users can logout', function () {
    $user = new User([
        'id' => '123456',
        'sub' => 'auth0|123456',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'user_id' => 'TestUser',
    ]);

    $this->actingAs($user);

    $response = $this->get('/logout');   // <-- GET instead of POST

    $response->assertRedirectContains('leadershipacademy.nhs.uk/v2/logout');

    $this->assertGuest();
});
