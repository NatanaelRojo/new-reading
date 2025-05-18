<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuthorResource\Forms\CreateForm;
use App\Filament\Resources\AuthorResource\Pages;
use App\Filament\Resources\AuthorResource\RelationManagers;
use App\Filament\Resources\AuthorResource\Tables\IndexTable;
use App\Models\API\V1\Author;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorResource extends Resource
{
    protected static ?string $model = Author::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $searchPlaceHolderMessage = 'Search by author\'s name...';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    /*************  ✨ Windsurf Command ⭐  *************/
    /**
     * Returns the table used for listing authors.
     *
     * @param \Filament\Tables\Table $table
     * @return \Filament\Tables\Table
     */
    /*******  59eb897f-30f9-4a79-9565-a3fb918dad56  *******/
    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
            ->columns(IndexTable::getColumns())
            ->filters(IndexTable::getFilters())
            ->actions(IndexTable::getActions())
            ->bulkActions(IndexTable::getBulkActions());
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuthors::route('/'),
            'create' => Pages\CreateAuthor::route('/create'),
            'edit' => Pages\EditAuthor::route('/{record}/edit'),
        ];
    }
}
