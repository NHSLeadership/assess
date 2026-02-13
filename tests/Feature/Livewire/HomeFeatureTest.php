<?php


use App\Livewire\Home;
use Livewire\Livewire;
use function Pest\Laravel\actingAs;

it('renders page normally for guests without redirect', function () {
    Livewire::test(Home::class)
        ->assertViewIs('livewire.home')
        ->assertNoRedirect();
});


it('redirects authenticated users to frameworks route', function () {
    $user = makeAuthUser();

    // Acting as a logged‑in user
    actingAs($user);

    Livewire::test(Home::class)
        ->assertRedirect(route('frameworks'));
});
