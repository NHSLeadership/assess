<?php

namespace App\Livewire;

use Livewire\Component;

class Review extends Component
{
    public string $hashId;

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.review');
    }
}
