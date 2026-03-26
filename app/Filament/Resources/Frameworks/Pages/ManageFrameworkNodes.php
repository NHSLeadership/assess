<?php

namespace App\Filament\Resources\Frameworks\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Openplain\FilamentTreeView\Resources\Pages\TreeRelationPage;

class ManageFrameworkNodes extends TreeRelationPage
{
    protected static string $resource = FrameworkResource::class;
    protected static string $relationship = 'nodes';
    protected static ?string $relatedResource = NodeResource::class;
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    #[\Override]
    protected function getHeaderActions(): array
    {
        return [
            Action::make('createNode')
                ->label('New node')
                ->url(fn (): string => NodeResource::getUrl('create', [
                    'framework' => $this->getRecord(),
                ])),
        ];
    }
}