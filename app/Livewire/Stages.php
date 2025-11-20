<?php

namespace App\Livewire;

use App\Models\Framework;
use App\Models\FrameworkVariantOption;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Stages extends Component
{
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

    #[Computed]
    public function stages(): ?Collection
    {
        if (empty($this->frameworkId) || ! is_numeric($this->frameworkId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->stages()->first()->options()->get();
    }

    #[Computed]
    public function stage(): ?FrameworkVariantOption
    {
        if (empty($this->stageId)) {
            return null;
        }

        return Framework::find($this->frameworkId)->stages()->first()->options()->where('id', $this->stageId)->first();
    }

    public function render()
    {
        return view('livewire.stages');
    }
}
