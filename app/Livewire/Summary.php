<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\Node;
use App\Models\Stage;
use App\Models\User;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Summary extends Component
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
    public function nodes(): ?Collection
    {
        return Node::where('framework_id', $this->frameworkId)->whereHas('questions')->get();
    }

    #[Computed]
    public function startedAreas(): ?Collection
    {
        if (empty($this->assessment)) {
            return null;
        }

        return $this->assessment?->framework?->nodes()->whereHas('questions')->get();
    }

    #[Computed]
    public function stage(): ?Stage
    {
        if (empty($this->frameworkId) || ! is_numeric($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->stages()->first()->options()->get();
    }

    #[Computed]
    public function stages(): Collection
    {
        return Framework::find($this->frameworkId)->stages()->options()->get();
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
    public function userResponses(): Collection
    {
        return $this->assessment->userResponses()->get();
    }

    public function render()
    {
        return view('livewire.summary', [
            'title' => 'Areas'
        ]);
    }
}
