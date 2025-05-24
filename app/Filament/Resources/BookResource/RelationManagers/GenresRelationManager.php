<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Filament\Resources\BookResource\Tables\GenreRelationManagerTable;
use App\Filament\Resources\GenreResource\Forms\CreateForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GenresRelationManager extends RelationManager
{
    protected static string $relationship = 'genres';
    protected static string $searchPlaceHolderMessage = 'Search by genre\'s name';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->genres()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
            ->columns(GenreRelationManagerTable::getColumns())
            ->filters(GenreRelationManagerTable::getFilters())
            ->headerActions(GenreRelationManagerTable::getHeaderActions())
            ->actions(GenreRelationManagerTable::getActions(relationManager: $this))
            ->bulkActions(GenreRelationManagerTable::getBulkActions());
    }
}
