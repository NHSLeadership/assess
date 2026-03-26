<?php

namespace App\Livewire;

use Livewire\Component;

class Alerts extends Component
{
    public $type;

    public $message;

    public $heading;

    // Listen for Livewire events
    protected $listeners = [
        'alert' => 'showAlert',
    ];

    public function showAlert($type, $message, $heading = null): void
    {
        $this->type = $type;
        $this->message = $message;
        $this->heading = $heading;
    }

    public function clearAlert(): void
    {
        $this->reset(['type', 'message', 'heading']);
    }

    public function render(): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('livewire.alerts');
    }
}
