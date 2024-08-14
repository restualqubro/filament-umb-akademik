<?php
 
namespace App\Filament\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Models\Surat;
use App\Models\Layanan\Cuti;
use App\Models\Layanan\Aktif;
use App\Models\Layanan\Pindah;
use App\Models\Layanan\Undur;
use App\Models\Layanan\Profesi;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
 
    public function filtersForm(Form $form): Form
    {
        return $form
            ->schema([         
                Section::make()                                            
                    ->schema([
                        Placeholder::make('')
                            ->content('Silahkan lengkapi profil anda terlebih dahulu untuk membuat Pengajuan Akademik pada halaman Profil')
                            
                    ])                               
                    ->columnSpan('full')
                    ->visible(fn(User $user, $record, GeneralSettings $setting): bool => (auth()->user()->roles->pluck('name')[0] === 'Mahasiswa' && $user->where([                                
                        ['telp', null],
                        ['birth_date', null],
                        ['birth_place', null],
                        ['address', null],
                        ['gender', null],
                        ['agama', null] 
                    ])->exists())),                                           
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
                            ])->disabled(fn(User $user, GeneralSettings $setting): bool => $user->where([                                
                                ['telp', null],
                                ['birth_date', null],
                                ['birth_place', null],
                                ['address', null],
                                ['gender', null],
                                ['agama', null] 
                            ])->exists())
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
                    ->hidden(fn(User $user, $record, GeneralSettings $settings): bool => (auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa' && $settings->akademik_active === '0' ))
                    ->columnSpan(2),                
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
                            ->disabled(fn(User $user): bool => $user->where([                                
                                ['telp', null],
                                ['birth_date', null],
                                ['birth_place', null],
                                ['address', null],
                                ['gender', null],
                                ['agama', null] 
                            ])->exists())
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
                    ->hidden(fn(User $user, $record): bool => (auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa'))
                    ->columnSpan(2),
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
                            ->disabled(fn(User $user): bool => $user->where([                                
                                ['telp', null],
                                ['birth_date', null],
                                ['birth_place', null],
                                ['address', null],
                                ['gender', null],
                                ['agama', null] 
                            ])->exists())
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
                    ->hidden(fn(User $user, $record): bool => (auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa'))
                    ->columnSpan(2),
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
                            ->disabled(fn(User $user): bool => $user->where([                                
                                ['telp', null],
                                ['birth_date', null],
                                ['birth_place', null],
                                ['address', null],
                                ['gender', null],
                                ['agama', null] 
                            ])->exists())
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
                    ->hidden(fn(User $user, $record): bool => (auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa'))
                    ->columnSpan(2),                                                                             
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
                            ->disabled(fn(User $user): bool => $user->where([                                
                                ['telp', null],
                                ['birth_date', null],
                                ['birth_place', null],
                                ['address', null],
                                ['gender', null],
                                ['agama', null] 
                            ])->exists())
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
                    ->hidden(fn(User $user, $record): bool => (auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa'))
                    ->columnSpan(2),
                // Halat                
                // Halat
            ])->columns(6);
    }
   
}