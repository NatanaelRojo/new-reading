<?php

namespace App\Filament\Resources\BookResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\ReviewsResource;
use App\Http\Resources\API\V1\Review\ReviewResource;
use App\Models\API\V1\Review;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\DissociateBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class ReviewRelationManagerTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('user.name')
                ->searchable(),
            TextColumn::make('rating'),
            TextColumn::make('comment'),
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
            AssociateAction::make()
                ->preloadRecordSelect()
                ->multiple(),
            CreateAction::make(),
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
            DissociateAction::make(),
                        ViewAction::make(),
            EditAction::make()
                ->url(
                    fn (Review $record): ?string => $isRelation ?
                    ReviewsResource::getUrl('edit', ['record' => $record])
                    : null
                ),
            DeleteAction::make(),
        ];
    }

    public static function getBulkActions(): array
    {
        return [
            BulkActionGroup::make([
                DissociateBulkAction::make(),
                DeleteBulkAction::make(),
            ]),
        ];
    }
}
