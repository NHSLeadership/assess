<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Contracts\Support\Htmlable;

class AssessmentsChart extends ChartWidget
{
    use InteractsWithPageFilters;

    public ?string $filter = 'month';
    protected static ?int $sort = 2;


    protected function getFilters(): ?array
    {
        return [
            'day' => 'Per day',
            'week' => 'Per week',
            'month' => 'Per month',
            'year' => 'Per year',
        ];
    }

    protected function applyFilters($query): void
    {
        $status = $this->filters['status'] ?? 'all';

        if ($status === 'started') {
            $query->whereNull('submitted_at');
        } elseif ($status === 'completed') {
            $query->whereNotNull('submitted_at');
        }

        $type = $this->filters['type'] ?? 'all';

        if ($type === '360') {
            $query->has('raters');
        } elseif ($type === 'self') {
            $query->doesntHave('raters');
        }
    }

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? '1/1/2026';
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();
        $interval = $this->filter ?? 'month';

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $builder = Assessment::query();

        $this->applyFilters($builder);

        /** @var 'day'|'week'|'month'|'year' $interval */
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

    public function getHeading(): Htmlable|string|null
    {
        $status = $this->filters['status'] ?? 'all';
        $type = $this->filters['type'] ?? 'all';
        $startDate = $this->filters['startDate'] ?? '1/1/2026';
        $endDate = $this->filters['endDate'] ?? now()->endOfMonth();

        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $typePrefix = match ($type) {
            '360' => '360',
            'self' => 'self',
            default => '',
        };

        $statusPrefix = match ($status) {
            'started' => 'started',
            'completed' => 'completed',
            default => '',
        };

        $title = trim("$statusPrefix $typePrefix");

        return ucfirst(
            ($title ? "$title assessments" : 'all assessments')
            . ' between '
            . $start->format('d F Y')
            . ' and '
            . $end->format('d F Y')
        );
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
