<?php

namespace App\Filament\Resources\GenreResource\RelationManagers;

use App\Filament\Resources\BookResource\Forms\CreateForm;
use App\Filament\Resources\GenreResource\Tables\BookRelationManagerTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BooksRelationManager extends RelationManager
{
    protected static string $relationship = 'books';
    protected static string $searchPlaceHolderMessage = 'Search by book\'s title';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->books()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
            ->columns(BookRelationManagerTable::getColumns())
            ->filters(BookRelationManagerTable::getFilters())
            ->headerActions(BookRelationManagerTable::getHeaderActions())
            ->actions(BookRelationManagerTable::getActions(relationManager: $this))
            ->bulkActions(BookRelationManagerTable::getBulkActions());
    }
}
