<?php

namespace App\Filament\Resources\Frameworks\Resources\Nodes;

use App\Filament\Resources\Frameworks\FrameworkResource;
use App\Filament\Resources\Frameworks\Resources\Nodes\Pages\CreateNode;
use App\Filament\Resources\Frameworks\Resources\Nodes\Pages\EditNode;
use App\Filament\Resources\Frameworks\Resources\Nodes\RelationManagers\QuestionsRelationManager;
use App\Filament\Resources\Frameworks\Resources\Nodes\RelationManagers\SignpostsRelationManager;
use App\Filament\Resources\Frameworks\Resources\Nodes\Schemas\NodeForm;
use App\Filament\Resources\Frameworks\Resources\Nodes\Tables\NodesTable;
use App\Models\Node;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Openplain\FilamentTreeView\Fields\TextField;
use Openplain\FilamentTreeView\Tree;

class NodeResource extends Resource
{
    protected static ?string $model = Node::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = FrameworkResource::class;


    public static function tree(Tree $tree): Tree
    {
        return $tree
            ->fields([
                TextField::make('name')
                    ->formatStateUsing(fn (string $state, \App\Models\Node $record): string =>
                    "{$record->type?->name}: {$state}"
                    ),
            ])
            ->recordActions([
                EditAction::make()->icon('heroicon-o-pencil-square'),
                Action::make('deleteNode')
                    ->label('Delete')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalDescription('Are you sure you want to delete this node? This action cannot be undone and will also delete all of its child nodes.')
                    ->action(function (Node $record): void {
                        $record->delete();
                    }),
            ])
            ->collapsible()
            ->maxDepth(5);
    }

    public static function form(Schema $schema): Schema
    {
        return NodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            QuestionsRelationManager::class,
            SignpostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateNode::route('/create'),
            'edit' => EditNode::route('/{record}/edit'),
        ];
    }
}
