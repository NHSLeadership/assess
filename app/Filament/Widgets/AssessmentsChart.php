<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\ChartWidget\Concerns\HasFiltersSchema;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AssessmentsChart extends ChartWidget
{
    use HasFiltersSchema;

    public function filtersSchema(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\Select::make('status')
                ->options([
                    'all' => 'All',
                    'started' => 'Started',
                    'completed' => 'Completed',
                ])
                ->default('all'),

            Forms\Components\DatePicker::make('startDate')
                ->minDate('1/1/2026')
                ->maxDate(now()->endOfMonth())
                ->default('1/1/2026'),

            Forms\Components\DatePicker::make('endDate')
                ->minDate('1/1/2026')
                ->maxDate(now()->endOfMonth())
                ->default(now()->endOfMonth()),

            Forms\Components\Select::make('interval')
                ->options([
                    'day' => 'Per day',
                    'week' => 'Per week',
                    'month' => 'Per month',
                    'year' => 'Per year',
                ])
                ->default('month'),
        ]);
    }

    protected function applyFilters($query): void
    {
        $status = $this->filters['status'] ?? 'all';

        if ($status === 'started') {
            $query->whereNull('submitted_at');
        } elseif ($status === 'completed') {
            $query->whereNotNull('submitted_at');
        }
    }

    protected function getData(): array
    {
        $status    = $this->filters['status'] ?? 'all';
        $startDate = $this->filters['startDate'] ?? '1/1/2026';
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();
        $interval      = $this->filters['interval'] ?? 'month';

        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);

        $builder = Assessment::query();
        $this->applyFilters($builder);

        $intervalMethod = match ($interval) {
            'day' => 'perDay',
            'week' => 'perWeek',
            'month' => 'perMonth',
            'year' => 'perYear',
        };

        $trend = Trend::query($builder)
            ->between(start: $start, end: $end)
            ->{$intervalMethod}()
            ->count();

        return [
            'datasets' => [
                [
                    'data' => $trend->map(fn (TrendValue $v) => $v->aggregate),
                    'backgroundColor' => '#9BD0F5',
                    'borderColor' => '#9BD0F5',
                ],
            ],
            'labels' => $trend->map(fn (TrendValue $v) => $v->date),
        ];
    }

    public function getHeading(): \Illuminate\Contracts\Support\Htmlable|string|null
    {
        $status    = $this->filters['status'] ?? 'all';
        $startDate = $this->filters['startDate'] ?? '1/1/2026';
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);
        return ucfirst($status) . ' assessments between ' . $start->format('d F Y') . ' and ' . $end->format('d F Y');
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
        ];
    }

    public function getColumnSpan(): int|string|array
    {
        return 'full';
    }
}
