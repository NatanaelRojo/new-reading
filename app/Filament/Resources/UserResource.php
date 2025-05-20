<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Forms\CreateForm;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\BooksRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PermissionsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\PostsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
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
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static string $searchPlaceHolderMessage = 'Search by email, or name';

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
            BooksRelationManager::class,
            PermissionsRelationManager::class,
            PostsRelationManager::class,
            RolesRelationManager::class,
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
