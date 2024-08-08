<?php

namespace App\Filament\Resources\Pengajuan\PindahResource\Pages;

use App\Filament\Resources\Pengajuan\PindahResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPindah extends EditRecord
{
    protected static string $resource = PindahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
