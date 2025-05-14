<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\PermissionResource\Forms\CreateForm;
use App\Filament\Resources\PermissionResource\Tables\IndexTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(IndexTable::getColumns())
            ->filters(IndexTable::getFilters())
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions(IndexTable::getActions(isRelation: true))
            ->bulkActions(IndexTable::getBulkActions());
    }
}
