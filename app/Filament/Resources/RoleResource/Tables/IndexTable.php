<?php

namespace App\Filament\Resources\RoleResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\RoleResource;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Spatie\Permission\Models\Role;

class IndexTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable(),
            TextColumn::make('guard_name'),
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
                    fn (Role $record): ?string => $isRelation ?
                    RoleResource::getUrl('edit', ['record' => $record])
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
