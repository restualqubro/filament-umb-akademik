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
use App\Models\Layanan\Cuti;
use App\Models\Layanan\Aktif;
use App\Models\Layanan\Pindah;
use App\Models\Layanan\Undur;
use App\Models\Layanan\Profesi;
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
                                Hidden::make('mahasiswa_id')
                                    ->label('Kode Mahasiswa')
                                    ->default(fn() => auth()->user()->id),
                                Hidden::make('akademik_id')
                                    ->label('Kode Akademik')
                                    ->default(function() {  
                                        $code = DB::table('settings')->where('name', 'akademik_id')->first()->payload;                                     
                                        return Str::substr($code, 1, 7 );
                                    })                                
                            ])
                            ->action(function (array $data): void {                                     
                                    $id = Str::ulid();                                    
                                    $record[] = array();
                                    $record['id'] = $id;
                                    $record['surat_id'] = $id;
                                    $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                    $record['slip_bebasspp'] = $data['slip_bebasspp'];
                                    $record['memo_perpus'] = $data['memo_perpus'];
                                    $record['alasan'] = $data['alasan'];
                                    $record['update_detail'] = 'Pengajuan Surat Cuti telah dibuat';
                                    $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                    $record['akademik_id']= $data['akademik_id'];
                                    $record['jenis'] = 'Cuti';
                                    Surat::Create($record);
                                    Cuti::Create($record);
                                    redirect('/');                                                                                                                          
                            }),
                            // ->action(fn ($record) => dd($record)),
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
                                    ->required(),   
                                Hidden::make('mahasiswa_id')
                                    ->label('Kode Mahasiswa')
                                    ->default(fn() => auth()->user()->id),
                                Hidden::make('akademik_id')
                                    ->label('Kode Akademik')
                                    ->default(function() {  
                                        $code = DB::table('settings')->where('name', 'akademik_id')->first()->payload;                                     
                                        return Str::substr($code, 1, 7 );
                                    })                                   
                            ])
                            ->action(function (array $data): void {                                     
                                $id = Str::ulid();                                    
                                $record[] = array();
                                $record['id'] = $id;
                                $record['surat_id'] = $id;                                
                                $record['slip_lunasspp'] = $data['slip_lunasspp'];
                                $record['surat_cuti'] = $data['surat_cuti'];                                
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Aktif dari Cuti';
                                Surat::Create($record);
                                Aktif::Create($record);
                                redirect('/');                                                                                                                          
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
                                Hidden::make('mahasiswa_id')
                                    ->label('Kode Mahasiswa')
                                    ->default(fn() => auth()->user()->id),
                                Hidden::make('akademik_id')
                                    ->label('Kode Akademik')
                                    ->default(function() {  
                                        $code = DB::table('settings')->where('name', 'akademik_id')->first()->payload;                                     
                                        return Str::substr($code, 1, 7 );
                                    })                                                                      
                            ])
                            ->action(function (array $data): void {                                     
                                $id = Str::ulid();                                    
                                $record[] = array();
                                $record['id'] = $id;
                                $record['surat_id'] = $id;
                                $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                $record['slip_bebasspp'] = $data['slip_bebasspp'];
                                $record['memo_perpus'] = $data['memo_perpus'];                                
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Pindah Perguruan Tinggi';
                                Surat::Create($record);
                                Pindah::Create($record);
                                redirect('/');                                                                                                                          
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
                                Hidden::make('mahasiswa_id')
                                    ->label('Kode Mahasiswa')
                                    ->default(fn() => auth()->user()->id),
                                Hidden::make('akademik_id')
                                    ->label('Kode Akademik')
                                    ->default(function() {  
                                        $code = DB::table('settings')->where('name', 'akademik_id')->first()->payload;                                     
                                        return Str::substr($code, 1, 7 );
                                    })                                                                
                            ])  
                            ->action(function (array $data): void {                                     
                                $id = Str::ulid();                                    
                                $record[] = array();
                                $record['id'] = $id;
                                $record['surat_id'] = $id;
                                $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                $record['slip_bebasspp'] = $data['slip_bebasspp'];
                                $record['memo_perpus'] = $data['memo_perpus'];
                                $record['alasan'] = $data['alasan'];
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Mengundurkan Diri';
                                Surat::Create($record);
                                Undur::Create($record);
                                redirect('/');                                                                                                                          
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
                                Hidden::make('mahasiswa_id')
                                    ->label('Kode Mahasiswa')
                                    ->default(fn() => auth()->user()->id),
                                Hidden::make('akademik_id')
                                    ->label('Kode Akademik')
                                    ->default(function() {  
                                        $code = DB::table('settings')->where('name', 'akademik_id')->first()->payload;                                     
                                        return Str::substr($code, 1, 7 );
                                    })                                                                                             
                            ])  
                            ->action(function (array $data): void {                                     
                                $id = Str::ulid();                                    
                                $record[] = array();
                                $record['id'] = $id;
                                $record['surat_id'] = $id;
                                $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                $record['slip_bebasspp'] = $data['slip_bebasspp'];
                                $record['memo_perpus'] = $data['memo_perpus'];                                
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Tidak Lanjut Profesi';
                                Surat::Create($record);
                                Profesi::Create($record);
                                redirect('/');                                                                                                                          
                        }),
                    ])
                    ->schema([                        
                    ])->columnSpan(2),
                // Halat                
                // Halat
            ])->columns(6);
    }
}