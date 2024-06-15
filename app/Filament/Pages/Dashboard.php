<?php
 
namespace App\Filament\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\TextEntry;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
 
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([                                                                  
                Section::make('Pengajuan Surat Cuti')    
                    ->description('Harap baca persyaratan dibawah sebelum mengajukan')                
                    ->headerActions([
                        Action::make('Ajukan')
                            ->form([
                                TextInput::make('Alasan')
                                    ->required(),                                    
                                FileUpload::make('surat_pernyataan')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
                                FileUpload::make('slip_bebasspp')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
                                FileUpload::make('memo_perpus')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required()
                            ])
                            ->action(function () {
                            }),
                    ])
                    ->schema([                        
                    ])->columnSpan(2),                
                // Halat                                                             
                Section::make('Pengajuan Surat Aktif')                    
                    ->description('Harap baca persyaratan dibawah sebelum mengajukan')                
                    ->headerActions([
                        Action::make('Ajukan')
                            ->form([
                                TextInput::make('Alasan')
                                    ->required(),                                    
                                FileUpload::make('surat_pernyataan')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
                                FileUpload::make('slip_bebasspp')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
                                FileUpload::make('memo_perpus')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required()
                            ])
                            ->action(function () {
                            }),
                    ])
                    ->schema([                        
                    ])->columnSpan(2),
                // halat
                Section::make('Pengajuan Surat Pindah')                    
                    
                    ->schema([                        
                    ])->columnSpan(2),
                // halat
                Section::make('Pengajuan Surat Mengundurkan Diri')                    
                    ->schema([                        
                    ])->columnSpan(2),                                                                             
                // Halat
                Section::make('Pengajuan Surat Tidak Lanjut Profesi')                    
                    
                    ->schema([                        
                    ])->columnSpan(2),
                // Halat
                Section::make('Pengajuan Surat Perbaikan Data')                    
                    
                    ->schema([                        
                    ])->columnSpan(2)
                // Halat
            ])->columns(6);
    }
}