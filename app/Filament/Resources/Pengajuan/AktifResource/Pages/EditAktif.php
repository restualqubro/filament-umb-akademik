<?php

namespace App\Filament\Resources\Pengajuan\AktifResource\Pages;

use App\Filament\Resources\Pengajuan\AktifResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAktif extends EditRecord
{
    protected static string $resource = AktifResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
