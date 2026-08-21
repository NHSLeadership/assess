<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\AssessmentRater;
use App\Models\RaterGroup;
use App\Traits\UserTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ManageRaterGroups extends Component
{
    use UserTrait;

    public int $assessmentId;

    public string $name = '';

    public ?int $editingGroupId = null;

    public function mount(int $assessmentId): void
    {
        $this->assessmentId = $assessmentId;
        $assessment = Assessment::query()
            ->whereKey($assessmentId)
            ->where('user_id', $this->user()->user_id)
            ->firstOrFail();
        $this->assessmentId = $assessment->id;
    }

    public function getGroupsProperty()
    {
        return RaterGroup::query()
            ->where('subject_id', $this->user()->user_id)
            ->withCount('assessmentRaters')
            ->orderBy('name')
            ->get();
    }

    public function editGroup(int $groupId): void
    {
        $group = RaterGroup::query()
            ->whereKey($groupId)
            ->where('subject_id', $this->user()->user_id)
            ->firstOrFail();
        $this->editingGroupId = $group->id;
        $this->name = $group->name;
    }

    public function saveGroup(): void
    {
        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('rater_groups', 'name')
                    ->where('subject_id', auth()->user()->user_id)
                    ->ignore($this->editingGroupId),
            ],
        ]);

        if ($this->editingGroupId) {
            RaterGroup::query()
                ->whereKey($this->editingGroupId)
                ->where('subject_id', $this->user()->user_id)
                ->firstOrFail()
                ->update([
                    'name' => $this->name,
                ]);
        } else {
            RaterGroup::create([
                'subject_id' => $this->user()->user_id,
                'name' => $this->name,
            ]);
        }

        $this->reset([
            'name',
            'editingGroupId',
        ]);
    }

    public function deleteGroup(int $groupId): void
    {
        $group = RaterGroup::query()
            ->whereKey($groupId)
            ->where('subject_id', $this->user()->user_id)
            ->firstOrFail();

        AssessmentRater::query()
            ->where('rater_group_id', $group->id)
            ->whereHas('assessment', fn ($query) => $query->where(
                'user_id',
                $this->user()->user_id
            ))
            ->update([
                'rater_group_id' => null,
            ]);

        $group->delete();
    }

    public function render()
    {
        return view('livewire.manage-rater-groups');
    }
}