<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Forms\CreateForm;
use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Filament\Resources\BookResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\BookResource\RelationManagers\ReviewsRelationManager;
use App\Filament\Resources\BookResource\RelationManagers\UsersRelationManager;
use App\Filament\Resources\BookResource\Tables\IndexTable;
use App\Models\API\V1\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $searchPlaceHolderMessage = 'Search by book\'s title...';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

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
            PostsRelationManager::class,
            ReviewsRelationManager::class,
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
