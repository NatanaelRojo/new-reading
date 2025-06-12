<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\AuthorResource;
use App\Models\API\V1\Author;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AssociateAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DissociateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;

class AuthorRelationManagerTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('full_name'),
            TextColumn::make('nationality'),
            TextColumn::make('biography')
                ->limit(30),
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
                ->hidden(
                    fn (RelationManager $livewire): bool =>
                    $livewire->getOwnerRecord()->author()->count() === 1
                ),
            CreateAction::make(),
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
            DissociateAction::make()
                ->visible(
                    fn (?Model $record): bool =>
                    $record->user()->count() === 1
                ),
                                    ViewAction::make(),
            EditAction::make()
                ->url(
                    fn (Author $record): ?string => $isRelation ?
                    AuthorResource::getUrl('edit', ['record' => $record])
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
            ])
        ];
    }
}
