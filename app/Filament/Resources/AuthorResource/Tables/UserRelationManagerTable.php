<?php

namespace App\Filament\Resources\AuthorResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\UserResource;
use App\Models\API\V1\Author;
use App\Models\User;
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

class UserRelationManagerTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name'),
            TextColumn::make('email'),        ];
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
                    $livewire->getOwnerRecord()->user()->count() === 1
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
                    $record->author()->count() === 1
                ),
                                    ViewAction::make(),
            EditAction::make()
                ->url(
                    fn (User $record): ?string => $isRelation ?
                    UserResource::getUrl('edit', ['record' => $record])
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
