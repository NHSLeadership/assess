<?php
namespace App\Traits;

use App\Models\User;
use Livewire\Attributes\Computed;
trait UserTrait
{
    #[Computed]
    public function user(): ?User
    {
//        $user = [
//            'name' => 'Marcin Calka',
//            'email' => 'marcin.calka@nhs.net',
//        ];
        $user = User::firstOrCreate(
        // Lookup attributes (used to check if the record exists)
            ['email' => 'mahesh.muralipoovampilly@nhs.net'],

            // Values to set if creating a new record
            [
                'name'     => 'Mahesh Murali Poovampilly',
                'password' => bcrypt(str()->password()), // always hash passwords
            ]
        );

//        $user->id = 1;

        return $user;
    }
}
