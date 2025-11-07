<?php
namespace App\Traits;

use App\Models\User;
use Livewire\Attributes\Computed;
trait UserTrait
{
    #[Computed]
    public function user(): ?User
    {
        $user = new User([
            'name' => 'Marcin Calka',
            'email' => 'marcin.calka@nhs.net',
        ]);
        $user->id = 1;

        return $user;
    }
}
