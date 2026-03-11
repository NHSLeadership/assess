<?php

namespace App\Filament\Resources\Frameworks\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use Openplain\FilamentTreeView\Resources\Pages\TreeRelationPage;

class ManageFrameworkNodes extends TreeRelationPage
{
    protected static string $resource = FrameworkResource::class;
    protected static string $relationship = 'nodes';
    protected static ?string $relatedResource = NodeResource::class;
}