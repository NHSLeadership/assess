<?php

namespace App\Filament\Resources\Frameworks\Widgets;

use App\Filament\Resources\Frameworks\Resources\Nodes\NodeResource;
use App\Models\Node;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Actions\CreateAction;
use SolutionForest\FilamentTree\Actions\DeleteAction;
use SolutionForest\FilamentTree\Widgets\Tree;
use SolutionForest\FilamentTree\Actions\EditAction;

class NodeTreeWidget extends Tree
{
    protected static string $model = Node::class;

    protected static int $maxDepth = 5;

    protected ?string $treeTitle = 'Nodes';

    protected bool $enableTreeTitle = true;

    public function getNodeCollapsedState(?Model $record = null): bool
    {
        return true;
    }

    protected function getTreeToolbarActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function hasEditAction(): bool
    {
        return true;
    }

    protected function configureEditAction(EditAction $action): EditAction
    {
        return $action
            ->icon('heroicon-o-pencil-square')
            ->link()
            ->url(fn (Node $record) => NodeResource::getUrl('edit', [
                'framework' => $record->framework,
                'record'    => $record,
            ]));
    }

    protected function hasDeleteAction(): bool
    {
        return true;
    }

    protected function configureDeleteAction(DeleteAction $action): DeleteAction
    {
        return $action
            ->icon('heroicon-o-trash')
            ->link();
    }
}
