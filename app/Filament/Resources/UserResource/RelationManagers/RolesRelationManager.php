<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\RoleResource\Forms\CreateForm;
use App\Filament\Resources\RoleResource\Tables\IndexTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RolesRelationManager extends RelationManager
{
    protected static string $relationship = 'roles';
    protected static string $searchPlaceHolderMessage = 'Search by role\'s name...';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->roles()->count();
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
