<?php

namespace App\View\Components;

use App\Models\Signpost;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class SignpostBanner extends Component
{
    public Collection $signposts;

    public string $bannerId;

    /**
     * @param  mixed  $signposts  Collection|array of \App\Models\Signpost
     * @param  string  $title  Visible title for the banner (used for aria-labelledby)
     */
    public function __construct($signposts = [], public string $title = 'Important')
    {
        $this->signposts = collect($signposts)
            ->filter(fn ($s): bool => $s instanceof Signpost)
            ->values();
        // unique id for aria-labelledby to avoid collisions on the page
        $this->bannerId = 'nhsuk-notification-banner-title-'.uniqid();
    }

    public function render()
    {
        return view('components.signpost.signpost-banner');
    }
}
