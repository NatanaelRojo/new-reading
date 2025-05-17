<?php

namespace App\Filament\Resources\CommentResource\Tables;

use App\Filament\Resources\Abstract\AbstractTable;
use App\Filament\Resources\CommentResource;
use App\Models\API\V1\Book;
use App\Models\API\V1\Comment;
use App\Models\API\V1\Post;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class IndexTable extends AbstractTable
{
    public const array COMMENTABLE_OPTIONS = [
        Book::class => 'Books',
        Post::class => 'Posts',
    ];

    public static function getColumns(): array
    {
        return [
            TextColumn::make('user.name')->searchable(),
            TextColumn::make('related_to'),
            TextColumn::make('body')->limit(50),
        ];
    }

    public static function getFilters(): array
    {
        return [
            SelectFilter::make('commentable_type')->label('Type')
                ->options(self::COMMENTABLE_OPTIONS),
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
                    fn (Comment $record): ?string => $isRelation ?
                    CommentResource::getUrl('edit', ['record' => $record])
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
