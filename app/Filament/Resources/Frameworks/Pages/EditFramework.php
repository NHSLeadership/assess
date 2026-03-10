<?php

namespace App\Filament\Resources\Frameworks\Pages;

use App\Filament\Resources\Frameworks\FrameworkResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFramework extends EditRecord
{
    protected static string $resource = FrameworkResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),

            Action::make('manageNodes')
                ->label('Nodes')
                ->url(fn () => FrameworkResource::getUrl('nodes', ['record' => $this->record])),

        ];
    }

    public function getSubNavigationParameters(): array
    {
        return ['record' => $this->record];
    }

}
