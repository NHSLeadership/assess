<?php

namespace Tests\Support;

use App\Livewire\Frameworks;
use App\Models\User;

class FrameworksFake extends Frameworks
{
    public ?User $mockedUser;

    public function setUser($user): void
    {
        $this->mockedUser = $user;
    }

    public function user(): ?User
    {
        return $this->mockedUser;
    }
}
