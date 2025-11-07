<?php

namespace App\Livewire;

use App\Models\Framework;
use App\Models\Stage;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Stages extends Component
{
    public ?string $stageId;

    #[Computed]
    public function frameworks(): Collection
    {
        if (empty($this->stageId) || ! is_numeric($this->stageId)) {
            return Framework::all();
        }

        return Framework::where('stage_id', $this->stageId)->get();
    }

    #[Computed]
    public function stages(): Collection
    {
        return Stage::all();
    }

    #[Computed]
    public function stage(): ?Stage
    {
        if (empty($this->stageId)) {
            return null;
        }

        return Stage::find($this->stageId);
    }

    public function render()
    {
        return view('livewire.stages');
    }
}
