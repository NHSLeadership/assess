<?php
namespace App\Traits;

use App\Models\User;
use Livewire\Attributes\Computed;
trait UserTrait
{
    #[Computed]
    public function user(): ?User
    {
        $authUser = auth()->user();
        $authUser->id = $authUser?->preferred_username;

        return new User($authUser->getAttributes());
    }
}
