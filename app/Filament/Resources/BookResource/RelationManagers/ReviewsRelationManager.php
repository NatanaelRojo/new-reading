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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';
    protected static string $searchPlaceHolderMessage = 'Search by user...';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->reviews()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('comment')
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
            ->columns(ReviewRelationManagerTable::getColumns())
            ->filters(ReviewRelationManagerTable::getFilters())
            ->headerActions(ReviewRelationManagerTable::getHeaderActions())
            ->actions(ReviewRelationManagerTable::getActions(relationManager: $this))
            ->bulkActions(ReviewRelationManagerTable::getBulkActions());
    }
}
