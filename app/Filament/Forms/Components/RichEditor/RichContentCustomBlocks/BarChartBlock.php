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
            ->modalDescription('Configure the bar chart block')
            ->schema([
                //
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
        $charts = $data['barChartsCompetency'] ?? [];

        $chart = collect($charts)->firstWhere('title', 'Personal impact');

        if (! $chart) {
            return '<p><em>Leadership Fundamentals chart not found.</em></p>';
        }

        return view(
            'filament.forms.components.rich-editor.rich-content-custom-blocks.bar-chart.index',
            ['chart' => $chart]
        )->render();
    }
}
