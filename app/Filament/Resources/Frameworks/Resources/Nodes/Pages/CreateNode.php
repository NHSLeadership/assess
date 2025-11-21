<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes\Pages;

use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateNode extends CreateRecord
{
    protected static string $resource = NodeResource::class;
}
