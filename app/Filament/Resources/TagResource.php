<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagResource\Forms\CreateForm;
use App\Filament\Resources\TagResource\Pages;
use App\Filament\Resources\TagResource\RelationManagers;
use App\Filament\Resources\TagResource\Tables\IndexTable;
use App\Models\API\V1\Tag;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static string $searchPlaceHolderMessage = 'Search by genre\'s name...';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(CreateForm::make());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->searchPlaceholder(static::$searchPlaceHolderMessage)
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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}
