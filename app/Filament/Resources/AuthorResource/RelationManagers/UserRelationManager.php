<?php

namespace App\Filament\Resources\AuthorResource\RelationManagers;

use App\Filament\Resources\AuthorResource\Tables\UserRelationManagerTable;
use App\Filament\Resources\UserResource\Forms\CreateForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserRelationManager extends RelationManager
{
    protected static string $relationship = 'user';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->user()->count();
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
            ->columns(UserRelationManagerTable::getColumns())
            ->filters(UserRelationManagerTable::getFilters())
            ->headerActions(UserRelationManagerTable::getHeaderActions())
            ->actions(UserRelationManagerTable::getActions($this))
            ->bulkActions(UserRelationManagerTable::getBulkActions());
    }
}
