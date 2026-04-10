<?php

namespace App\Filament\Resources\RetentionEvents;

use App\Filament\Resources\RetentionEvents\Pages\CreateRetentionEvent;
use App\Filament\Resources\RetentionEvents\Pages\EditRetentionEvent;
use App\Filament\Resources\RetentionEvents\Pages\ListRetentionEvents;
use App\Filament\Resources\RetentionEvents\Pages\ViewRetentionEvent;
use App\Filament\Resources\RetentionEvents\Schemas\RetentionEventForm;
use App\Filament\Resources\RetentionEvents\Schemas\RetentionEventInfolist;
use App\Filament\Resources\RetentionEvents\Tables\RetentionEventsTable;
use App\Models\RetentionEvent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RetentionEventResource extends Resource
{
    protected static ?string $model = RetentionEvent::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return RetentionEventForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RetentionEventInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RetentionEventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRetentionEvents::route('/'),
            'view' => ViewRetentionEvent::route('/{record}'),
        ];
    }
}
