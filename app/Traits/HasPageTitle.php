<?php

namespace App\Traits;

use Livewire\Attributes\On;

trait HasPageTitle
{
    protected string $pageTitle = '';

    public function mountHasPageTitle(): void
    {
        $this->dispatch(
            'page-title',
            title: $this->pageTitle
        );
    }

}
