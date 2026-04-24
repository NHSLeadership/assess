<?php

namespace App\Filament\Resources\Frameworks\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Icons\Heroicon;

class EditFramework extends EditRecord
{
    protected static string $resource = FrameworkResource::class;
    protected static ?string $navigationLabel = 'Framework';
    protected static string|null|\BackedEnum $navigationIcon = Heroicon::OutlinedLifebuoy;

    protected function getHeaderActions(): array
    {
        $actions = [];

        if ($this->record?->hasAssessments()) {
            $count = $this->record->assessments()->count();
            $actions[] = DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete framework')
                ->modalDescription("This framework is currently in use by {$count} assessment(s). Are you sure you want to delete it?");
        } else {
            $actions[] = DeleteAction::make();
        }

        return $actions;
    }
}
