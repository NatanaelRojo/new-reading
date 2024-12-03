<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewsResource\Forms\CreateForm;
use App\Filament\Resources\ReviewsResource\Pages;
use App\Filament\Resources\ReviewsResource\RelationManagers;
use App\Filament\Resources\ReviewsResource\Tables\IndexTable;
use App\Models\API\V1\Review;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ReviewsResource extends Resource
{
    protected static ?string $model = Review::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(IndexTable::getColumns())
            ->filters(IndexTable::getFilters())
            ->actions(IndexTable::getActions())
            ->bulkActions(IndexTable::getBulkActions());
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReviews::route('/create'),
            'edit' => Pages\EditReviews::route('/{record}/edit'),
        ];
    }
}
