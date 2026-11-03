<?php

function resolveTranslatorWithRebrand(bool $enabled): void
{
    config(['app.rebrand_profile_id' => $enabled]);
    app()->forgetInstance('translator');
}

it('shows Academy ID when the rebrand flag is off', function () {
    resolveTranslatorWithRebrand(false);

    expect(__('branding.profile_id'))->toBe('Academy ID')
        ->and(__('branding.profile_id_lower'))->toBe('Academy id');

    $this->get('/')
        ->assertOk()
        ->assertSee('Sign in with Academy ID')
        ->assertDontSee('previously known as Academy ID');
});

it('shows Profile ID when the rebrand flag is on', function () {
    resolveTranslatorWithRebrand(true);

    expect(__('branding.profile_id'))->toBe('Profile ID')
        ->and(__('branding.profile_id_lower'))->toBe('Profile id');

    $this->get('/')
        ->assertOk()
        ->assertSee('Sign in with Profile ID')
        ->assertSee('previously known as Academy ID');
});
