<?php

namespace App\Filament\Resources\UserResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use App\Filament\Resources\AuthorResource\Forms\CreateForm as FormsCreateForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;

class CreateForm extends AbstractCreateForm
{
    public static function make(): array
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
                TextInput::make('email')
                ->required()
                ->email(),
            TextInput::make('name')
                ->required(),
            TextInput::make('description')
                ->required(),
            TextInput::make('password')
                ->required(),
            DatePicker::make('birth_date')
                ->required(),
        ];
    }
}
