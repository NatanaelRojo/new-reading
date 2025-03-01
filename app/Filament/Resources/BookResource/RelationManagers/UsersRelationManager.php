<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Filament\Resources\BookResource\Tables\UserRelationManagerTable;
use App\Filament\Resources\UserResource\Forms\CreateForm;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
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
            ->columns(UserRelationManagerTable::getColumns())
            ->filters(UserRelationManagerTable::getFilters())
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions(UserRelationManagerTable::getActions())
            ->bulkActions(UserRelationManagerTable::getBulkActions());
    }
}
