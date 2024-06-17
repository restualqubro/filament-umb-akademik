<?php
 
namespace App\Filament\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Models\Surat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                                    ->required(),
                                TextInput::make('mahasiswa_id')
                                    ->label('Kode Mahasiswa')
                                    ->default(fn() => auth()->user()->id)
                                    ->hidden(),
                                TextInput::make('akademik_id')
                                    ->label('Kode Akademik')
                                    ->default(function() {  
                                        $code = DB::table('settings')->where('name', 'akademik_id')->first()->payload;                                     
                                        return Str::substr($code, 1, );
                                    })                                
                            ])
                            ->action(fn (Surat $record) => $record->save),
                    ])
                    ->schema([                        
                    ])->columnSpan(2),                
                // Halat                                                             
                Section::make('Pengajuan Surat Aktif')                    
                    ->description('Harap baca persyaratan dibawah sebelum mengajukan')                
                    ->headerActions([
                        Action::make('Ajukan')
                            ->form([
                                FileUpload::make('surat_cuti')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
                                FileUpload::make('slip_lunasspp')      
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
                    ->description('Harap baca persyaratan dibawah sebelum mengajukan')                
                    ->headerActions([
                        Action::make('Ajukan')
                            ->form([                                                            
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
                                    ->required(),                                
                                FileUpload::make('transkip_nilai')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
                                Select::make('jenis_pt')
                                    ->options([
                                        'Universitas'       => 'UNIVERSITAS',
                                        'Politeknik'        => 'POLITEKNIK',
                                        'Sekolah Tinggi'    => 'SEKOLAH TINGGI'
                                    ])
                                    ->preload(),
                                TextInput::make('nama_pt')
                                    ->required()                                    
                            ])
                            ->action(function () {
                            }),
                    ])
                    ->schema([                        
                    ])->columnSpan(2),
                // halat
                Section::make('Pengajuan Surat Mengundurkan Diri') 
                    ->description('Harap baca persyaratan dibawah sebelum mengajukan')                
                    ->headerActions([
                        Action::make('Ajukan')
                            ->form([                                                            
                                TextInput::make('alasan')      
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
                Section::make('Pengajuan Surat Tidak Lanjut Profesi')                    
                    ->description('Harap baca persyaratan dibawah sebelum mengajukan')                
                    ->headerActions([
                        Action::make('Ajukan')
                            ->form([                                                            
                                TextInput::make('alasan')      
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
                                    ->required(),
                                FileUpload::make('bebas_lab')      
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
                Section::make('Pengajuan Surat Perbaikan Data')                    
                    
                    ->schema([                        
                    ])->columnSpan(2)
                // Halat
            ])->columns(6);
    }
}