<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Framework;
use App\Models\FrameworkVariantAttribute;
use App\Models\Node;
use App\Traits\UserTrait;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Summary extends Component
{
    use UserTrait;
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
        return Node::where('framework_id', $this->frameworkId)->orderBy('order')->orderBy('id')->get();
        //return Node::where('framework_id', $this->frameworkId)->orderByRaw('coalesce(parent_id, id), `order`')->orderBy('order')->get();
    }


    #[Computed]
    public function responses(): ?Collection
    {
        return $this->assessment?->responses()?->get();
    }

    /**
     * Redirect to edit answers for a specific node
     */
    public function editAnswer($nodeId)
    {
        if (!is_numeric($nodeId)) {
            return null;
        }
        return redirect()->route('questions', [
            'assessmentId' => $this->assessmentId,
            'nodeId' => $nodeId,
            'edit' => 'edit',
        ]);
    }

    public function render()
    {
        return view('livewire.summary', [
            'title' => 'Areas'
        ]);
    }
}
