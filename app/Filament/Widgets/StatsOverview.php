<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getFilteredQuery()
    {
        $query = Assessment::query();

        $type = $this->filters['type'] ?? 'all';
        $startDate = $this->filters['startDate'] ?? '1/1/2026';
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        if ($type === '360') {
            $query->has('raters');
        } elseif ($type === 'self') {
            $query->doesntHave('raters');
        }

        $query->whereBetween('created_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);

        return $query;
    }

    protected function getStats(): array
    {
        $query = $this->getFilteredQuery();

        return [
            Stat::make(
                'Started assessments',
                (clone $query)->whereNull('submitted_at')->count()
            ),

            Stat::make(
                'Completed assessments',
                (clone $query)->whereNotNull('submitted_at')->count()
            ),

            Stat::make(
                'Total assessments',
                (clone $query)->count()
            ),
        ];
    }
}
