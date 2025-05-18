<?php

namespace App\Filament\Resources\RoleResource\RelationManagers;

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
            ->columns(IndexTable::getColumns())
            ->filters(IndexTable::getFilters())
            ->headerActions(IndexTable::getHeaderActions())
            ->actions(IndexTable::getActions(relationManager: $this))
            ->bulkActions(IndexTable::getBulkActions());
    }
}
