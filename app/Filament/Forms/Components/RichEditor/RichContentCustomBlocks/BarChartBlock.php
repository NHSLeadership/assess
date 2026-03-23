<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use Filament\Actions\Action;
use Filament\Forms\Components\RichEditor\RichContentCustomBlock;

class BarChartBlock extends RichContentCustomBlock
{
    public static function getId(): string
    {
        return 'bar_chart';
    }

    public static function getLabel(): string
    {
        return 'Bar chart';
    }

    public static function configureEditorAction(Action $action): Action
    {
        return $action
            ->modalDescription('Choose an area to display')
            ->schema([
                \Filament\Forms\Components\Select::make('title')
                    ->label('Choose area')
                    ->options(function () {
                        // Titles used in barChartsCompetency
                        return [
                            'Managing people and resources' => 'Managing people and resources',
                            'Personal impact' => 'Personal impact',
                            // add others here...
                        ];
                    })
                    ->required(),
            ]);
    }

    public static function toPreviewHtml(array $config): string
    {
        return view('filament.forms.components.rich-editor.rich-content-custom-blocks.bar-chart.preview', [
            //
        ])->render();
    }

    public static function toHtml(array $config, array $data): string
    {
        $charts = $data['barCharts'] ?? [];

        if (empty($charts)) {
            return '<p><em>No chart data found.</em></p>';
        }

        // Use config selected by editor
        $requestedTitle = $config['title'] ?? null;

        if ($requestedTitle) {
            $chart = collect($charts)->firstWhere('title', $requestedTitle);
        } else {
            // Default fallback
            $chart = $charts[0] ?? null;
        }

        if (! $chart) {
            return '<p><em>No matching chart found.</em></p>';
        }

        return view(
            'filament.forms.components.rich-editor.rich-content-custom-blocks.bar-chart.index',
            ['chart' => $chart]
        )->render();
    }
}
