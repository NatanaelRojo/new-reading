<?php

namespace App\Filament\Resources\UserResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;

class IndexTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('email')
                ->searchable(),
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('description')
                ->limit(30),
            TextColumn::make('birth_date')
                ->date(),
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
            Tables\Actions\CreateAction::make(),
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
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
            ]),
        ];
    }
}
