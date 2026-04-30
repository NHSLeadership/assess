<?php

namespace App\Traits;
trait HasPageTitle
{
    protected string $pageTitle = '';

    /**
     * Livewire lifecycle hook – runs only on initial mount
     *
     * @return void
     */
    public function mountHasPageTitle(): void
    {
        $this->dispatchPageTitle();
    }

    protected function dispatchPageTitle(?string $title = null): void
    {
        $title = trim($title ?? $this->pageTitle);

        if ($title === '') {
            return;
        }
        $title = config('app.page_title_prefix') . $title;
        $this->dispatch('page-title', title: $title);
    }
}
