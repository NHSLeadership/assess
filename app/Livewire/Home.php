<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\Attributes\Title;

class Home extends Component
{
    use UserTrait;

    public ?string $frameworkId;

    public ?string $stageId;

    #[Computed]
    public function framework(): ?Framework
    {
        if (empty($this->frameworkId)) {
            $framework = Framework::first();
            $this->frameworkId = $framework->id;

            return $framework;
        }

        return Framework::find($this->frameworkId);
    }

    #[Title('Frameworks')]
    public function render()
    {
        return view('livewire.home');
    }
}
