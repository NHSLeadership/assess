<?php

namespace App\Livewire;

use Livewire\Component;

class Home extends Component
{
    public function mount()
    {
        if (auth()->check()) {
            return redirect()->route('frameworks');
        }
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.home');
    }
}
