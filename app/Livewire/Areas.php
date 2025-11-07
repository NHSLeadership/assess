<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Area;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Areas extends Component
{
    public $frameworkId;
    public $assessmentId;

    #[Computed]
    public function framework(): ?Framework
    {
        if (empty($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId);
    }

    #[Computed]
    public function frameworks(): Collection
    {
        return Framework::all();
    }

    #[Computed]
    public function assessment(): ?Assessment
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return Assessment::find($this->assessmentId);
    }

    #[Computed]
    public function areas(): ?Collection
    {
        return Area::where('framework_id', $this->frameworkId)->whereHas('fields')->get();
    }

    #[Computed]
    public function startedAreas(): ?Collection
    {
        if (empty($this->assessment)) {
            return null;
        }

        return $this->assessment?->framework?->areas()->whereHas('fields')->get();
    }

    #[Computed]
    public function stage(): ?Stage
    {
        if (empty($this->frameworkId)) {
            return null;
        }

        return $this->framework()->stage;
    }

    #[Computed]
    public function stages(): Collection
    {
        return Stage::all();
    }

    #[Computed]
    public function user(): ?User
    {
        $user = new User([
            'name' => 'Marcin Calka',
            'email' => 'marcin.calka@nhs.net',
        ]);
        $user->id = 1;

        return $user;
    }

    #[Computed]
    public function userData(): Collection
    {
        return Assessment::where('id', $this->assessmentId)->first()->userDataOptions()->get();
    }

    public function render()
    {
        return view('livewire.areas', [
            'title' => 'Areas'
        ]);
    }
}
