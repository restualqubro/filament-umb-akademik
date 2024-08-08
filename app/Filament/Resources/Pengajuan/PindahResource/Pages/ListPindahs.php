<?php

namespace App\Filament\Resources\Pengajuan\PindahResource\Pages;

use App\Filament\Resources\Pengajuan\PindahResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPindahs extends ListRecords
{
    protected static string $resource = PindahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
