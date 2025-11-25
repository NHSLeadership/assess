<?php

namespace App\Livewire;

use Livewire\Component;

class Review extends Component
{
    public string $hashId;

    public function render()
    {
        return view('livewire.review');
    }
}
