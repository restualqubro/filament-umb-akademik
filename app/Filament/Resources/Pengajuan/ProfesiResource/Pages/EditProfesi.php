<?php

namespace App\Filament\Resources\Pengajuan\ProfesiResource\Pages;

use App\Filament\Resources\Pengajuan\ProfesiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProfesi extends EditRecord
{
    protected static string $resource = ProfesiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
