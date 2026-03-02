<?php

namespace App\Filament\Resources\Frameworks\Widgets;

use App\Enums\NodeColour;
use App\Models\Node;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Database\Eloquent\Model;
use SolutionForest\FilamentTree\Actions\CreateAction;
use SolutionForest\FilamentTree\Widgets\Tree;

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

    protected function getFormSchema(): array
    {
        return [
            Select::make('node_type_id')
                ->relationship('type', 'name')
                ->createOptionForm([
                    TextInput::make('name')
                        ->required(),
                ])
                ->required(),
            TextInput::make('name')
                ->required(),
            ToggleButtons::make('colour')
                ->inline()
                ->options(NodeColour::options())
                ->colors(NodeColour::colours())
                ->required(),
            RichEditor::make('description')
                ->columnSpanFull(),
        ];
    }

    protected function getTreeToolbarActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function hasDeleteAction(): bool
    {
        return true;
    }

    protected function hasEditAction(): bool
    {
        return true;
    }

    protected function hasViewAction(): bool
    {
        return true;
    }
}
