<?php

namespace App\Filament\Resources\Pengajuan\UndurResource\Pages;

use App\Filament\Resources\Pengajuan\UndurResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUndurs extends ListRecords
{
    protected static string $resource = UndurResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
