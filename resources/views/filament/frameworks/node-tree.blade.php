<div class="mt-6">
    @livewire(
        \App\Filament\Resources\Frameworks\Widgets\NodeTreeWidget::class,
        ['record' => $record],
        key('node-tree-' . $record->id)
    )
</div>