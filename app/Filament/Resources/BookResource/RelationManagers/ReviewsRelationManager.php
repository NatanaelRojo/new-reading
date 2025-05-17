<?php

namespace App\Filament\Resources\BookResource\RelationManagers;

use App\Filament\Resources\BookResource\Tables\ReviewRelationManagerTable;
use App\Filament\Resources\ReviewsResource\Forms\CreateForm;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->columns(ReviewRelationManagerTable::getColumns())
            ->filters(ReviewRelationManagerTable::getFilters())
            ->headerActions(ReviewRelationManagerTable::getHeaderActions())
            ->actions(ReviewRelationManagerTable::getActions(relationManager: $this))
            ->bulkActions(ReviewRelationManagerTable::getBulkActions());
    }
}
