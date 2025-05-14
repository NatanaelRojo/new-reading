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
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UsersRelationManager extends RelationManager
{
    protected static string $relationship = 'users';

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(IndexTable::getTableColumns())
            ->filters(IndexTable::getTableFilters())
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions(IndexTable::getTableActions())
            ->bulkActions(IndexTable::getTableBulkActions());
    }
}
