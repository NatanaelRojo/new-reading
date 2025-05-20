<?php

namespace App\Filament\Resources\ReviewsResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Http\Resources\API\V1\Review\ReviewResource;
use App\Models\API\V1\Review;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class IndexTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('user.name')
                ->searchable(),
            TextColumn::make('book.title')
                ->searchable(),
            TextColumn::make('rating'),
            TextColumn::make('comment')
                ->limit(30),
            TextColumn::make('like_count'),
            TextColumn::make('dislike_count'),
        ];
    }

    public static function getFilters(): array
    {
        return [
            //
        ];
    }

    public static function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
                        ViewAction::make(),
            EditAction::make()
                ->url(
                    fn (Review $record): ?string => $isRelation ?
                    ReviewResource::getUrl('edit', ['record' => $record])
                    : null
                ),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
