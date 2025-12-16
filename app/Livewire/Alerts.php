<?php

namespace App\Livewire;

use Livewire\Component;

class Alerts extends Component
{
    public $type = null;
    public $message = null;
    public $heading = null;

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
    public function render()
    {
        return view('livewire.alerts');
    }
}