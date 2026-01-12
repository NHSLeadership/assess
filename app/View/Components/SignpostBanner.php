<?php
namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;

class SignpostBanner extends Component
{
    public Collection $signposts;
    public string $title;
    public string $bannerId;

    /**
     * @param mixed $signposts Collection|array of \App\Models\Signpost
     * @param string $title Visible title for the banner (used for aria-labelledby)
     */
    public function __construct($signposts = [], $title = 'Important')
    {
        $this->signposts = collect($signposts)
            ->filter(fn($s) => $s instanceof \App\Models\Signpost)
            ->values();

        $this->title = $title;
        // unique id for aria-labelledby to avoid collisions on the page
        $this->bannerId = 'nhsuk-notification-banner-title-' . uniqid();
    }

    public function render()
    {
        return view('components.signpost.signpost-banner');
    }
}
