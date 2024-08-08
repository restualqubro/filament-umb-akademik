<?php

namespace App\Filament\Resources\Pengajuan\UndurResource\Pages;

use App\Filament\Resources\Pengajuan\UndurResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUndur extends EditRecord
{
    protected static string $resource = UndurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
