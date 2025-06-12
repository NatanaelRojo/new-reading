<?php

namespace App\Filament\Resources\AuthorResource\Forms;

use App\Filament\Resources\Abstract\AbstractCreateForm;
use App\Filament\Resources\BookResource\Forms\CreateForm as FormsCreateForm;
use App\Filament\Resources\UserResource\Forms\CreateForm as UserResourceFormsCreateForm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class CreateForm extends AbstractCreateForm
{
    public static function make(): array
    {
        return [
            TextInput::make('first_name')
                ->required(),
            TextInput::make('last_name')
                ->required(),
            TextInput::make('nationality')
                ->required(),
            Textarea::make('biography')
                ->required(),
                FileUpload::make('image')
                ->avatar()
                ->imageEditor(),
        ];
    }
}
