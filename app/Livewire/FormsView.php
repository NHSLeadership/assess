<?php

namespace App\Livewire;

use App\Models\Assessment;
use App\Models\Form;
use App\Models\Stage;
use App\Models\User;
use App\Models\UserDataOption;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

class FormsView extends Component
{
    public $assessmentId = 1;

    public function mount(): void
    {
        //
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
    public function assessments(): Collection
    {
        return Assessment::all();
    }

    #[Computed]
    public function forms(): ?Collection
    {
        return Form::with('assessment')->whereHas('assessment', function ($query) {
            $query->where('id', $this->assessmentId);
        })->get();
    }

    #[Computed]
    public function stage(): ?Stage
    {
        if (empty($this->assessmentId)) {
            return null;
        }

        return $this->assessment()->stage;
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
        return UserDataOption::where('user_id', $this->user->id)
            ->whereIn('form_id', $this->forms->pluck('id'))
            ->get();
    }

    public function render()
    {
        return view('livewire.forms-view');
    }
}
