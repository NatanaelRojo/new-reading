<?php

namespace App\Filament\Resources\PermissionResource\RelationManagers;

use App\Filament\Resources\UserResource\Forms\CreateForm;
use App\Filament\Resources\UserResource\Tables\IndexTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';
    protected static string $searchPlaceHolderMessage = 'Search by name, or email';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->users()->count();
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
            ->columns(IndexTable::getColumns())
            ->filters(IndexTable::getFilters())
            ->headerActions(IndexTable::getHeaderActions())
            ->actions(IndexTable::getActions(relationManager: $this))
            ->bulkActions(IndexTable::getBulkActions());
    }
}
