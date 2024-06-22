<?php

namespace App\Filament\Resources\TahunAkademikResource\Pages;

use App\Filament\Resources\TahunAkademikResource;
use Filament\Forms\Form;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Resources\Pages\Page;

class StatusAkademik extends Page
{
    protected static string $resource = TahunAkademikResource::class;

    // protected static string $view = 'filament.resources.tahun-akademik-resource.pages.status-akademik';

    use HasFiltersForm;
 
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([                                                                                  
                // Halat
            ])->columns(6);
    }
}
