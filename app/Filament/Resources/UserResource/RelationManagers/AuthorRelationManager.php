<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Filament\Resources\AuthorResource\Forms\CreateForm;
use App\Filament\Resources\UserResource\Tables\AuthorRelationManagerTable;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuthorRelationManager extends RelationManager
{
    protected static string $relationship = 'author';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->author()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('full_name')
            ->columns(AuthorRelationManagerTable::getColumns())
            ->filters(AuthorRelationManagerTable::getFilters())
            ->headerActions(AuthorRelationManagerTable::getHeaderActions())
            ->actions(AuthorRelationManagerTable::getActions($this))
            ->bulkActions(AuthorRelationManagerTable::getBulkActions());
    }
}
