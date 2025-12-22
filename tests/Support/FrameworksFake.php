<?php

namespace Tests\Support;

use App\Livewire\Frameworks;

class FrameworksFake extends Frameworks
{
    public ?\App\Models\User $mockedUser;

    public function setUser($user): void
    {
        $this->mockedUser = $user;
    }

    public function user(): ?\App\Models\User
    {
        return $this->mockedUser;
    }
}
