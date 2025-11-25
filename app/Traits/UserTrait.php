<?php
namespace App\Traits;

use App\Models\User;
use Livewire\Attributes\Computed;
trait UserTrait
{
    #[Computed]
    public function user(): ?User
    {
        $user = User::firstOrCreate(
            ['email' => 'mahesh.muralipoovampilly@nhs.net'],
            [
                'name'     => 'Mahesh Murali Poovampilly',
                'password' => bcrypt(str()->password()), // always hash passwords
            ]
        );

        return $user;
    }
}
