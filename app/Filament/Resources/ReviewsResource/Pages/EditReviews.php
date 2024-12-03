<?php

namespace App\Filament\Resources\ReviewsResource\Pages;

use App\Filament\Resources\ReviewsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReviews extends EditRecord
{
    protected static string $resource = ReviewsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
