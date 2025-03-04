<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Forms\CreateForm;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\BooksRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\UserResource\Tables\IndexTable;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(IndexTable::getTableColumns())
            ->filters(IndexTable::getTableFilters())
            ->actions(IndexTable::getTableActions())
            ->bulkActions(IndexTable::getTableBulkActions());
    }

    public static function getRelations(): array
    {
        return [
            BooksRelationManager::class,
            PostsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
