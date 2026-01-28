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
    
    public function render()
    {
        return view('livewire.home');
    }
}
