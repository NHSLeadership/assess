<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Started assessments', Assessment::where('submitted_at', null)->count())
//                ->chart()
            ,
            Stat::make('Completed assessments', Assessment::where('submitted_at', '!=', null)->count()),
            Stat::make('Total assessments', Assessment::count()),
        ];
    }
}