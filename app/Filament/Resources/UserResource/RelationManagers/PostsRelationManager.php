<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\PostResource\Forms\CreateForm;
use App\Filament\Resources\UserResource\Forms\PostRelationManagerCreateForm;
use App\Filament\Resources\UserResource\Tables\PostRelationManagerTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PostsRelationManager extends RelationManager
{
    protected static string $relationship = 'posts';
    protected static string $searchPlaceHolderMessage = 'Search by book\'s title...';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->posts()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('body')
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
            ->columns(PostRelationManagerTable::getColumns())
            ->filters(PostRelationManagerTable::getFilters())
            ->headerActions(PostRelationManagerTable::getHeaderActions())
            ->actions(PostRelationManagerTable::getActions(relationManager: $this))
            ->bulkActions(PostRelationManagerTable::getBulkActions());
    }
}
