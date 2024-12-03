<?php

namespace App\Filament\Resources\ReviewsResource\Pages;

use App\Filament\Resources\ReviewsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReviews extends CreateRecord
{
    protected static string $resource = ReviewsResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
