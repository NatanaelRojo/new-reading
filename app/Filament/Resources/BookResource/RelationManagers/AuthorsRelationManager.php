<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Filament\Resources\AuthorResource\Forms\CreateForm;
use App\Filament\Resources\BookResource\Tables\AuthorRelationManagerTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorsRelationManager extends RelationManager
{
    protected static string $relationship = 'authors';
    protected static string $searchPlaceHolderMessage = 'Search by author\'s first name or last name...';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->authors()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
            ->columns(AuthorRelationManagerTable::getColumns())
            ->filters(AuthorRelationManagerTable::getFilters())
            ->headerActions(AuthorRelationManagerTable::getHeaderActions())
            ->actions(AuthorRelationManagerTable::getActions(relationManager: $this))
            ->bulkActions(AuthorRelationManagerTable::getBulkActions());
    }
}
