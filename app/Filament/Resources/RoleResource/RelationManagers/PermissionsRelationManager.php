<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

use App\Filament\Resources\PermissionResource\Forms\CreateForm;
use App\Filament\Resources\PermissionResource\Tables\IndexTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';
    protected static string $searchPlaceHolderMessage = 'Search by permission\'s name...';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->permissions()->count();
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
