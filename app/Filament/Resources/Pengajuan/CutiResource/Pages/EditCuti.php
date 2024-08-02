<?php

namespace App\Filament\Resources\Pengajuan\CutiResource\Pages;

use App\Filament\Resources\Pengajuan\CutiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuti extends EditRecord
{
    protected static string $resource = CutiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
