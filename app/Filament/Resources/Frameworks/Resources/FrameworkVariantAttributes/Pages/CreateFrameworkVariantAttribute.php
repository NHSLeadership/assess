<?php

namespace App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use App\Filament\Resources\Frameworks\Resources\FrameworkVariantAttributes\FrameworkVariantAttributeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFrameworkVariantAttribute extends CreateRecord
{
    protected static string $resource = FrameworkVariantAttributeResource::class;

    protected function getRedirectUrl(): string
    {
        // Go back to the parent Framework's page
        return FrameworkResource::getUrl('edit', [
            'record'   => $this->getParentRecord(),
            'relation' => 'variants',  // Navigate to Variant Attributes tab
        ]);
    }
}
