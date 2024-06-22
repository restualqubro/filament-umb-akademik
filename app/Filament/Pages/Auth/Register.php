<?php

namespace App\Filament\Pages\Auth;

// use Filament\Actions\Concerns\CanUseDatabaseTransactions;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Auth\Register as BaseRegister;
use Filament\Pages\Concerns\InteractsWithFormActions;

class Register extends BaseRegister
{

    // use CanUseDatabaseTransactions;
    // use InteractsWithFormActions;
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';

    // protected static string $view = 'filament.pages.auth.register';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
    
}
