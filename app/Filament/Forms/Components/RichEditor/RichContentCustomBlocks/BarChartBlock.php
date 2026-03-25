<?php

namespace App\Filament\Forms\Components\RichEditor\RichContentCustomBlocks;

use App\Models\Framework;
use App\Models\Node;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
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
            ->modalDescription('Choose a node to display a bar chart for its immediate children')
            ->schema([
                Select::make('node_id')
                    ->label('Node')
                    ->options(function (?Framework $record): array {
                        if (! $record?->id) {
                            return [];
                        }

                        return Node::query()
                            ->where('framework_id', $record->id)
                            ->whereNull('parent_id')
                            ->orderBy('order')
                            ->orderBy('id')
                            ->pluck('name', 'id')
                            ->toArray();
                    })
                    ->searchable()
                    ->required(),
            ]);
    }

    public static function toPreviewHtml(array $config): string
    {
        $nodeId = $config['node_id'] ?? null;

        return $nodeId
            ? '<p><em>Bar chart (node #' . e((string) $nodeId) . ')</em></p>'
            : '<p><em>Bar chart (choose a node)</em></p>';
    }

    public static function toHtml(array $config, array $data): string
    {
        $charts = $data['barCharts'] ?? [];

        if (empty($charts)) {
            return '<p><em>No chart data found.</em></p>';
        }

        $nodeId = isset($config['node_id']) ? (int) $config['node_id'] : null;

        $chart = $nodeId
            ? collect($charts)->firstWhere('node_id', $nodeId)
            : ($charts[0] ?? null);

        if (! $chart) {
            return '<p><em>No matching chart found for that node.</em></p>';
        }

        return view(
            'filament.forms.components.rich-editor.rich-content-custom-blocks.bar-chart.index',
            ['chart' => $chart]
        )->render();
    }
}
