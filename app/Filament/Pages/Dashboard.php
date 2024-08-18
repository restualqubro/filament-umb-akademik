<?php
 
namespace App\Filament\Pages;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Forms\Form;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use App\Models\Surat;
use App\Models\Layanan\Cuti;
use App\Models\Layanan\Aktif;
use App\Models\Layanan\Pindah;
use App\Models\Layanan\Undur;
use App\Models\Layanan\Profesi;
use App\Models\Data\TahunAkademik;
use App\Models\User;
use App\Models\Mahasiswa;
use App\Settings\GeneralSettings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;

class Dashboard extends BaseDashboard
{
    use HasFiltersForm;
 
    public function filtersForm(Form $form): Form
    {
        $tahunakademik = TahunAkademik::get();
        return $form
            ->schema([   
                Select::make('code')    
                    ->label('Filter Semester')                  
                    ->options(                                              
                            $tahunakademik->mapWithKeys(function (TahunAkademik $tahunakademik) {
                                return [$tahunakademik->code => sprintf('%s %s', $tahunakademik->tahun, $tahunakademik->semester)];
                            })
                            )->visible(fn() => (auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa')),
                Section::make()                                            
                    ->schema([
                        Placeholder::make('')
                            ->content('Silahkan lengkapi profil anda terlebih dahulu untuk membuat Pengajuan Akademik pada halaman Profil')
                            
                    ])                               
                    ->columnSpan('full')
                    ->visible(fn() => (auth()->user()->roles->pluck('name')[0] === 'Mahasiswa' && (auth()->user()->isComplete === FALSE || auth()->user()->mahasiswa->isComplete === FALSE ))),                                                           
                    Section::make('Pengajuan Surat Cuti')                                                                                
                    ->schema([
                        Placeholder::make('')
                            ->content(fn() => new HtmlString('
                            Harap baca persyaratan dibawah sebelum mengajukan<br/><br/>
                            1. Melengkapi Biodata sesuai DATA SIAKAD pada halaman <a class="underline" href="/my-profile" target="_blank">Profil</a><br/>
                            2. [Dokumen] Scan PDF/Foto Jelas Surat Pernyataan Orang tua<br/>
                            3. [Dokumen] Scan PDF/Foto Jelas Slip Bebas SPP dari Keuangan<br/>
                            4. [Dokumen] Scan PDF/Foto Jelas Memo Perpustakaan<br/><br/>
                            <small style="color:#ff0000;">*</small> Dokumen Surat Pernyataan orang tua dapat diunduh <a class="underline" href="/documents/surat-keterangan-orang-tua.docx" target="_blank">disini</a>'                            
                            ))
                        ])
                    ->collapsible()
                    // ->description('Harap baca persyaratan dibawah sebelum mengajukan')                                    
                    ->headerActions([
                        Action::make('Ajukan')
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Pengajuan Berhasil')
                                    ->body('Pengajuan Cuti kamu berhasil dikirim'),
                            )
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
                            })
                            ->disabled(fn() => (auth()->user()->isComplete === FALSE || auth()->user()->mahasiswa->isComplete === FALSE)),                                           
                            // ->action(fn ($record) => dd($record)),
                    ])
                    ->hidden(fn(Surat $surat): bool => (    auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa' || 
                                                            $surat->where('mahasiswa_id', auth()->user()->id)
                                                                ->where('status', '!=', 'Baru')
                                                                ->where('status', '!=', 'Ditolak')
                                                                ->where('status', '!=', 'Disetujui')
                                                                ->count()))
                    ->columnSpan(2),              
                // Halat                                                             
                Section::make('Pengajuan Surat Aktif')   
                    ->collapsible()                 
                    ->schema([
                        Placeholder::make('')
                            ->content(fn() => new HtmlString('
                            Harap baca persyaratan dibawah sebelum mengajukan<br/><br/>
                            1. Melengkapi Biodata sesuai DATA SIAKAD pada halaman <a class="underline" href="/my-profile" target="_blank">Profil</a><br/>
                            2. [Dokumen] Scan PDF/Foto Jelas Surat Pernyataan Orang tua<br/>
                            3. [Dokumen] Scan PDF/Foto Jelas Surat Cuti Sebelumnya<br/>
                            4. [Dokumen] Scan PDF/Foto Jelas Slip Lunas SPP Keuangan<br/><br/>
                            <small style="color:#ff0000;">*</small> Dokumen Surat Pernyataan orang tua dapat diunduh <a class="underline" href="/documents/surat-keterangan-orang-tua.docx" target="_blank">disini</a>'                            
                            ))
                        ])
                    ->headerActions([
                        Action::make('Ajukan')
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Pengajuan Berhasil')
                                    ->body('Pengajuan Cuti kamu berhasil dikirim'),
                            )
                            ->form([
                                FileUpload::make('surat_pernyataan')      
                                    ->maxSize(200)  
                                    ->acceptedFileTypes(['application/pdf', 'application/msword', 'image/jpeg', 'image/jpg', 'image/png'])                            
                                    ->required(),
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
                                $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                $record['slip_lunasspp'] = $data['slip_lunasspp'];
                                $record['surat_cuti'] = $data['surat_cuti'];                                
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Aktif dari Cuti';
                                Surat::Create($record);
                                Aktif::Create($record);
                                redirect('/');                                                                                                                          
                        })
                        ->disabled(fn() => (auth()->user()->isComplete === FALSE || auth()->user()->mahasiswa->isComplete === FALSE)),                                
                    ])
                    ->hidden(fn(Surat $surat): bool => (    auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa' || 
                                                            $surat->where('mahasiswa_id', auth()->user()->id)
                                                                ->where('status', '!=', 'Baru')
                                                                ->where('status', '!=', 'Ditolak')
                                                                ->where('status', '!=', 'Disetujui')
                                                                ->count()))
                    ->columnSpan(2),
                // halat
                Section::make('Pengajuan Surat Pindah') 
                    ->collapsible()                   
                    ->schema([
                        Placeholder::make('')
                            ->content(fn() => new HtmlString('
                            Harap baca persyaratan dibawah sebelum mengajukan<br/><br/>
                            1. Melengkapi Biodata sesuai DATA SIAKAD pada halaman <a class="underline" href="/my-profile" target="_blank">Profil</a><br/>
                            2. [Dokumen] Scan PDF/Foto Jelas Surat Pernyataan Orang tua<br/>
                            3. [Dokumen] Scan PDF/Foto Jelas Surat Cuti Sebelumnya<br/>
                            4. [Dokumen] Scan PDF/Foto Jelas Slip Lunas SPP Keuangan<br/><br/>
                            <small style="color:#ff0000;">*</small> Dokumen Surat Pernyataan orang tua dapat diunduh <a class="underline" href="/documents/surat-keterangan-orang-tua.docx" target="_blank">disini</a>'                            
                            ))
                        ])
                    ->headerActions([
                        Action::make('Ajukan')
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Pengajuan Berhasil')
                                    ->body('Pengajuan Cuti kamu berhasil dikirim'),
                            )
                            ->form([       
                                TextInput::make('alasan')
                                    ->label('Alasan Pengajuan Pindah Perguruan Tinggi')
                                    ->required(),
                                Select::make('jenis')
                                    ->label('Jenis Perguruan Tinggi Tujuan')
                                    ->options([
                                        'Universitas'   => 'Universitas',
                                        'Politeknik'    => 'Politeknik',
                                        'Sekolah Tinggi'=> 'Sekolah Tinggi'
                                    ])
                                    ->required()
                                    ->searchable(),
                                TextInput::make('tujuan')
                                    ->label('Nama Perguruan Tinggi Tujuan')
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
                                $record['alasan'] = $data['alasan'];
                                $record['jenis'] = $data['jenis'];
                                $record['tujuan'] = $data['tujuan'];
                                $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                $record['slip_bebasspp'] = $data['slip_bebasspp'];
                                $record['memo_perpus'] = $data['memo_perpus'];                                
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Pindah Perguruan Tinggi';
                                Surat::Create($record);
                                Pindah::Create($record);
                                redirect('/');                                                                                                                          
                        })->disabled(fn() => (auth()->user()->isComplete === FALSE || auth()->user()->mahasiswa->isComplete === FALSE)),                                           
                    ])
                    ->hidden(fn(Surat $surat): bool => (    auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa' || 
                                                            $surat->where('mahasiswa_id', auth()->user()->id)
                                                                ->where('status', '!=', 'Baru')
                                                                ->where('status', '!=', 'Ditolak')
                                                                ->where('status', '!=', 'Disetujui')
                                                                ->count()))
                    ->columnSpan(2),
                // halat
                Section::make('Pengajuan Surat Mengundurkan Diri') 
                    ->collapsible()
                    ->schema([
                        Placeholder::make('')
                            ->content(fn() => new HtmlString('
                            Harap baca persyaratan dibawah sebelum mengajukan<br/><br/>
                            1. Melengkapi Biodata sesuai DATA SIAKAD pada halaman <a class="underline" href="/my-profile" target="_blank">Profil</a><br/>
                            2. [Dokumen] Scan PDF/Foto Jelas Surat Pernyataan Orang tua<br/>
                            3. [Dokumen] Scan PDF/Foto Jelas Surat Cuti Sebelumnya<br/>
                            4. [Dokumen] Scan PDF/Foto Jelas Slip Lunas SPP Keuangan<br/><br/>
                            <small style="color:#ff0000;">*</small> Dokumen Surat Pernyataan orang tua dapat diunduh <a class="underline" href="/documents/surat-keterangan-orang-tua.docx" target="_blank">disini</a>'                            
                            ))
                        ])
                    ->headerActions([
                        Action::make('Ajukan')
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Pengajuan Berhasil')
                                    ->body('Pengajuan Cuti kamu berhasil dikirim'),
                            )
                            ->form([                      
                                TextInput::make('alasan')
                                    ->label('Alasan Pengajuan Mengundurkan Diri')                                                                      
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
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Mengundurkan Diri';
                                Surat::Create($record);
                                Undur::Create($record);
                                redirect('/');                                                                                                                          
                        })->disabled(fn() => (auth()->user()->isComplete === FALSE || auth()->user()->mahasiswa->isComplete === FALSE)),                                           
                    ])
                    ->hidden(fn(Surat $surat): bool => (    auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa' || 
                                                            $surat->where('mahasiswa_id', auth()->user()->id)
                                                                ->where('status', '!=', 'Baru')
                                                                ->where('status', '!=', 'Ditolak')
                                                                ->where('status', '!=', 'Disetujui')
                                                                ->count()))
                    ->columnSpan(2),                                                                             
                // Halat
                Section::make('Pengajuan Surat Tidak Lanjut Profesi') 
                    ->collapsible()                  
                    ->schema([
                        Placeholder::make('')
                            ->content(fn() => new HtmlString('
                            Harap baca persyaratan dibawah sebelum mengajukan<br/><br/>
                            1. Melengkapi Biodata sesuai DATA SIAKAD pada halaman <a class="underline" href="/my-profile" target="_blank">Profil</a><br/>
                            2. [Dokumen] Scan PDF/Foto Jelas Surat Pernyataan Orang tua<br/>
                            3. [Dokumen] Scan PDF/Foto Jelas Surat Cuti Sebelumnya<br/>
                            4. [Dokumen] Scan PDF/Foto Jelas Slip Lunas SPP Keuangan<br/><br/>
                            <small style="color:#ff0000;">*</small> Dokumen Surat Pernyataan orang tua dapat diunduh <a class="underline" href="/documents/surat-keterangan-orang-tua.docx" target="_blank">disini</a>'                            
                            ))
                        ])
                    ->headerActions([
                        Action::make('Ajukan')
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Pengajuan Berhasil')
                                    ->body('Pengajuan Cuti kamu berhasil dikirim'),
                            )
                            ->form([ 
                                TextInput::make('alasan')
                                    ->label('Alasan Pengajuan Tidak Lanjut Profesi')                                                                      
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
                                $record['alasan'] = $data['alasan'];
                                $record['surat_pernyataan'] = $data['surat_pernyataan'];
                                $record['slip_bebasspp'] = $data['slip_bebasspp'];
                                $record['memo_perpus'] = $data['memo_perpus'];                                
                                $record['mahasiswa_id'] = $data['mahasiswa_id'];
                                $record['akademik_id']= $data['akademik_id'];
                                $record['jenis'] = 'Tidak Lanjut Profesi';
                                Surat::Create($record);
                                Profesi::Create($record);
                                redirect('/');                                                                                                                          
                        })->disabled(fn() => (auth()->user()->isComplete === FALSE || auth()->user()->mahasiswa->isComplete === FALSE)),                                           
                    ])
                    ->hidden(fn(Surat $surat): bool => (    auth()->user()->roles->pluck('name')[0] !== 'Mahasiswa' || 
                                                            $surat->where('mahasiswa_id', auth()->user()->id)
                                                                ->where('status', '!=', 'Baru')
                                                                ->where('status', '!=', 'Ditolak')
                                                                ->where('status', '!=', 'Disetujui')
                                                                ->count()))
                    ->columnSpan(2),
                // Halat                
                // Halat
            ])->columns(6);
    }
   
}