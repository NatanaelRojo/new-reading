<?php

namespace App\Filament\Resources\BookResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\UserResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Tag;
use App\Models\User;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Collection;

class UserRelationManagerTable extends AbstractTable
{
    public static function getColumns(): array
    {
        return [
            TextColumn::make('name')
                ->searchable(),
            SelectColumn::make('tag_id')
                ->label('tag')
                ->options(fn (): Collection => Tag::query()->pluck('name', 'id')),
                TextColumn::make('pages_read'),
                TextColumn::make('reading_percentage')
                    ->suffix('%')
                    ->getStateUsing(function (?User $record): string {
                        return $record->getBookCompletionPercentage($record->book_id);
                    })
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
            AttachAction::make()
                ->preloadRecordSelect()
                ->multiple(),
            CreateAction::make(),
        ];
    }

    public static function getActions(?RelationManager $relationManager = null): array
    {
        $isRelation = $relationManager instanceof RelationManager;

        return [
            DetachAction::make(),
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
                DetachBulkAction::make(),
                DeleteBulkAction::make(),
            ]),
        ];
    }

}
