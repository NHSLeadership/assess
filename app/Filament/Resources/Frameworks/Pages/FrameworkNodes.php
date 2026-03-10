<?php

namespace App\Filament\Resources\Frameworks\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use Filament\Actions\Action;
use Openplain\FilamentTreeView\Resources\Pages\TreeRelationPage;

class FrameworkNodes extends TreeRelationPage
{
    protected static string $resource = FrameworkResource::class;

    protected static string $relationship = 'nodes';

    protected static ?string $relatedResource = \App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('backToFramework')
                ->label('Back to framework')
                ->icon('heroicon-o-chevron-left')
                ->url(fn () => FrameworkResource::getUrl(
                    'edit',
                    ['record' => $this->getRecord()]
                ))
                ->color('gray'),
            Action::make('createNode')
                ->label('New node')
                ->url(fn () => NodeResource::getUrl('create', [
                    'framework' => $this->getRecord(),
                ])),
        ];
    }
}
