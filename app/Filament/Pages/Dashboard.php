<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Schemas\Schema;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;

    // Hide dashboard heading
    public function getHeading(): ?string
    {
        return null;
    }
    public function filtersForm(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('status')
                ->options([
                    'all' => 'All',
                    'started' => 'Started',
                    'completed' => 'Completed',
                ])
                ->default('all'),

            Select::make('type')
                ->options([
                    'all' => 'All',
                    'self' => 'Self',
                    '360' => '360',
                ])
                ->default('all'),

            DatePicker::make('startDate')
                ->minDate('1/1/2026')
                ->maxDate(now()->endOfMonth())
                ->default('1/1/2026'),

            DatePicker::make('endDate')
                ->minDate('1/1/2026')
                ->maxDate(now()->endOfMonth())
                ->default(now()->endOfMonth()),

            Select::make('interval')
                ->options([
                    'day' => 'Per day',
                    'week' => 'Per week',
                    'month' => 'Per month',
                    'year' => 'Per year',
                ])
                ->default('month'),
        ]);
    }
}
