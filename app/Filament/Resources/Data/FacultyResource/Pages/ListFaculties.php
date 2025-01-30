<?php

namespace App\Filament\Resources\Data\FacultyResource\Pages;

use App\Filament\Resources\Data\FacultyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFaculties extends ListRecords
{
    protected static string $resource = FacultyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Add Fakultas')
                ->icon('heroicon-o-plus'),
        ];
    }
}
