<?php

namespace App\Filament\Resources\Pengajuan;

use App\Filament\Resources\Pengajuan\AktifResource\Pages;
use App\Filament\Resources\Pengajuan\AktifResource\RelationManagers;
use App\Models\Layanan\Aktif;
use App\Models\Surat;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class AktifResource extends Resource
{
    protected static ?string $model = Aktif::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Pengajuan Aktif dari Cuti';

    protected static ?string $navigationGroup = 'Pengajuan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([      
                Tables\Columns\TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),  
                Tables\Columns\TextColumn::make('surat.mahasiswa.username')                    
                    ->searchable()
                    ->label('NPM'),
                Tables\Columns\TextColumn::make('surat.mahasiswa.name')
                    ->label('NAMA')
                    ->searchable(),                
                Tables\Columns\TextColumn::make('surat.akademik.name')
                    ->label('SEMESTER')
                    ->sortable(),
                Tables\Columns\TextColumn::make('surat.mahasiswa.mahasiswa.prodi.nama_prodi')
                    ->label('PRODI'),
                Tables\Columns\TextColumn::make('surat.status')
                    ->label('STATUS SURAT')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Verifikasi' => 'warning',
                        'Validasi Dosen' => 'secondary',
                        'Validasi Kaprodi' => 'secondary',
                        'Validasi Dekan' => 'secondary',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Perbaikan' => 'info',
                        'Diperbaiki' => 'info'                            
                    }),                
            ])            
            ->filters([
                Tables\Filters\SelectFilter::make('akademik_id') 
                    ->label('Kode Akademik')                                       
                    ->relationship(name: 'surat', titleAttribute: 'akademik_id'),
                Tables\Filters\SelectFilter::make('prodi_id')
                    ->label('Prodi')
                    ->relationship(name: 'surat.mahasiswa.mahasiswa.prodi', titleAttribute: 'nama_prodi')           
            ])
            ->actions([
                Tables\Actions\ViewAction::make('detail'),                
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                        
                        $record[] = array();                        
                        $record['operator_id'] = auth()->user()->id;
                        $record['status'] = "Verifikasi";
                        Surat::where('id', $surat['surat_id'])->update([
                            'operator_id'   => $record['operator_id'],
                            'update_detail' => 'Pengajuan anda telah mendapatkan Verifikasi dari Operator Akademik',
                            'status'        => $record['status'],                            
                        ]);
                    })->visible(fn(Aktif $record) => $record->surat->status === 'Baru' || $record->surat->status === 'Diperbaiki' && auth()->user()->roles->pluck('name')[0] === 'Operator'),
                Tables\Actions\Action::make('validasi_dosen')
                    ->label('Validasi Dosen')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                        
                        $record[] = array();                        
                        $record['dosen_id'] = auth()->user()->id;
                        $record['status'] = "Validasi Dosen";
                        Surat::where('id', $surat['surat_id'])->update([                            
                            'update_detail' => 'Pengajuan anda telah mendapatkan Validasi dari Dosen Pembimbing Akademik',
                            'status'        => $record['status'],                            
                        ]);
                        Aktif::where('id', $surat['id'])->update([
                            'dosen_id'      => $record['dosen_id']
                        ]);
                    })->visible(fn (Aktif $record) => $record->surat->status === 'Verifikasi' && auth()->user()->roles->pluck('name')[0] === 'Dosen'),
                Tables\Actions\Action::make('validasi_kaprodi')
                    ->label('Validasi Kaprodi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                        
                        $record[] = array();                        
                        $record['kaprodi_id'] = auth()->user()->id;
                        $record['status'] = "Validasi Kaprodi";
                        Surat::where('id', $surat['surat_id'])->update([ 
                            'update_detail' => 'Pengajuan anda telah mendapatkan Validasi dari Kepala Program Studi',                           
                            'status'        => $record['status'],                            
                        ]);
                        Aktif::where('id', $surat['id'])->update([
                            'kaprodi_id'      => $record['kaprodi_id']
                        ]);
                    })->visible(fn (Aktif $record) => $record->surat->status === 'Validasi Dosen' && auth()->user()->roles->pluck('name')[0] === 'Kaprodi'),
                Tables\Actions\Action::make('validasi_dekan')
                    ->label('Validasi Dekan')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                        
                        $record[] = array();                        
                        $record['dekan_id'] = auth()->user()->id;
                        $record['status'] = "Validasi Dekan";
                        Surat::where('id', $surat['surat_id'])->update([ 
                            'update_detail' => 'Pengajuan anda telah mendapatkan Validasi dari Dekan Fakultas',                           
                            'status'        => $record['status'],                            
                        ]);
                        Aktif::where('id', $surat['id'])->update([
                            'dekan_id'      => $record['dekan_id']
                        ]);
                    })->visible(fn (Aktif $record) => $record->surat->status === 'Validasi Dosen' && auth()->user()->roles->pluck('name')[0] === 'Kaprodi'),
                Tables\Actions\Action::make('disetujui')
                    ->label('Disetujui')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                        
                        $month = Carbon::now()->format('m');                        
                        $map = array('X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
                        $monthRomanian = '';
                        while ($month > 0) {
                            foreach ($map as $roman => $int) {
                                if($month >= $int) {
                                    $month -= $int;
                                    $monthRomanian .= $roman;
                                    break;
                                }
                            }
                        }                                               
                        $last = Aktif::whereHas('surat', function($q) {
                            $date = Carbon::now()->format('Y-m');
                            $q->where('status', 'Disetujui');                            
                        })
                        ->whereMonth('updated_at', '=', date('m'))
                        ->whereYear('updated_at', '=', date('Y'))
                        ->max('no_surat');                                                
                        // $last = Cuti::whereRaw("MID(no_surat, 16, 4) = $date")->max('code');                                        
                        // dd($last);
                        $code = '';
                        if ($last != null) {                                                                                            
                            $tmp = substr($last, 0, 3)+1;
                            $code = sprintf("%03s", $tmp)."/UM-BJM/S.1/".$monthRomanian."/".Carbon::now()->format('Y');                                                                            
                        } else {
                            $code = "001/UM-BJM/S.2/".$monthRomanian."/".Carbon::now()->format('Y');
                        }                        
                        // $record['wrektor_id'] = auth()->user()->id;                        
                        // $record['status'] = "Disetujui";
                        Surat::where('id', $surat['surat_id'])->update([  
                            'update_detail' => 'Pengajuan anda telah mendapatkan Persetujuan dari Wakil Rektor 1 dan anda dapat menggunakan Surat Aktif dari Cuti ini sebagai Syarat Sah',                          
                            'status'        => 'Disetujui',                            
                        ]);
                        Aktif::where('id', $surat['id'])->update([
                            'wrektor_id'    => auth()->user()->id, 
                            'no_surat'      =>  $code,                          
                        ]);
                    })->visible(fn (Aktif $record) => $record->surat->status === 'Validasi Dekan' && auth()->user()->roles->pluck('name')[0] === 'Wakil Rektor'), 
                Tables\Actions\Action::make('perbaikan')
                    ->label('Perbaikan')               
                    ->icon('heroicon-o-pencil-square')
                    ->color('info')
                    ->form([
                        Textarea::make('detail')
                                    ->required(),
                    ])
                    ->action(function (array $data, Aktif $surat): void {                        
                        Surat::where('id', $surat['surat_id'])->update([  
                            'update_detail' => 'Pengajuan anda di tunda karena terdapat kesalahan sebagai berikut : '.$data['detail'],                          
                            'status'        => 'Perbaikan',                            
                            'operator_id'   => auth()->user()->id,
                        ]);                        
                    })->visible(fn (Aktif $record) => $record->surat->status === 'Baru' || $record->surat->status === 'Diperbaiki' && auth()->user()->roles->pluck('name')[0] === 'Operator'),                                       
                Tables\Actions\Action::make('ditolak_operator')
                    // ->label('Ditolak')               
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                                                
                        Surat::where('id', $surat['surat_id'])->update([  
                            'update_detail' => 'Pengajuan anda Ditolak oleh pihak Operator Akademik',                          
                                'status'        => 'Ditolak',                            
                        ]);                                                                      
                    })->visible(fn (Aktif $record) => $record->surat->operator_id === null && auth()->user()->roles->pluck('name')[0] === 'Operator'),
                Tables\Actions\Action::make('ditolak_dosen')
                    // ->label('Ditolak')               
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                                                
                        Aktif::where('surat_id', $surat['surat_id'])->update([                          
                            'status'        => 'Ditolak',                            
                        ]);                                              
                        Surat::where('id', $surat['surat_id'])->update([
                            'update_detail' => 'Pengajuan anda Ditolak oleh pihak Dosen Pembimbing Akademik',                          
                        ]);                        
                    })->visible(fn (Aktif $record) => $record->dosen_id === null && auth()->user()->roles->pluck('name')[0] === 'Dosen'),
                Tables\Actions\Action::make('ditolak_kaprodi')
                    // ->label('Ditolak')               
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                                                
                        Aktif::where('surat_id', $surat['surat_id'])->update([                          
                            'status'        => 'Ditolak',                            
                        ]);                                              
                        Surat::where('id', $surat['surat_id'])->update([
                            'update_detail' => 'Pengajuan anda Ditolak oleh pihak Kepala Program Studi',                          
                        ]);                                                                       
                    })->visible(fn (Aktif $record) => $record->kaprodi_id === null && auth()->user()->roles->pluck('name')[0] === 'Kaprodi'),
                Tables\Actions\Action::make('ditolak_dekan')
                    // ->label('Ditolak')               
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                                                
                        Aktif::where('surat_id', $surat['surat_id'])->update([                          
                            'status'        => 'Ditolak',                            
                        ]);                                              
                        Surat::where('id', $surat['surat_id'])->update([
                            'update_detail' => 'Pengajuan anda Ditolak oleh pihak Dekan Fakultas',                          
                        ]); 
                    })->visible(fn (Aktif $record) => $record->dekan_id === null && auth()->user()->roles->pluck('name')[0] === 'Dekan'),
                Tables\Actions\Action::make('ditolak_wrektor')
                    // ->label('Ditolak')               
                    ->icon('heroicon-o-no-symbol')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (array $data, Aktif $surat): void {                                                                                                
                        Aktif::where('surat_id', $surat['surat_id'])->update([                          
                            'status'        => 'Ditolak',                            
                        ]);                                              
                        Surat::where('id', $surat['surat_id'])->update([
                            'update_detail' => 'Pengajuan anda Ditolak oleh pihak Wakil Rektor 1',                          
                        ]); 
                    })->visible(fn (Aktif $record) => $record->wrektor_id === null && auth()->user()->roles->pluck('name')[0] === 'Wakil Rektor'),
                Tables\Actions\Action::make('print')
                    // ->label('Ditolak')               
                    ->icon('heroicon-o-printer')
                    ->color('success')                    
                    ->url(function(Aktif $record) {
                        return url('report/aktif/'.$record->surat_id);
                    })
                    ->openUrlInNewTab()  
                    ->visible(fn (Aktif $record) => $record->surat->status === 'Disetujui'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
        {
            return $infolist
                ->schema([
                    TextEntry::make('surat.mahasiswa.username')
                        ->label('NPM Mahasiswa'),
                    TextEntry::make('surat.mahasiswa.name')
                        ->label('Nama Mahasiswa'), 
                    TextEntry::make('surat.mahasiswa.mahasiswa.prodi.nama_prodi')
                        ->label('Program Studi'),
                    TextEntry::make('surat.akademik.name')
                        ->label('Semester'),
                    TextEntry::make('surat.status')
                        ->label('Status Pengajuan')
                        ->badge()
                        ->color(fn (string $state): string => match ($state) {
                            'Baru' => 'gray',
                            'Verifikasi' => 'warning',
                            'Validasi Dosen' => 'secondary',
                            'Validasi Kaprodi' => 'secondary',
                            'Validasi Dekan' => 'secondary',
                            'Disetujui' => 'success',
                            'Ditolak' => 'danger',
                            'Perbaikan' => 'info',
                            'Diperbaiki' => 'info'                            
                        }),
                    TextEntry::make('alasan')
                        ->label('Alasan Pengajuan'),
                    TextEntry::make('updated_at')
                        ->label('Timestamp Update Terakhir'),
                    TextEntry::make('surat.update_detail')
                        ->label('Detail Update'),                   
                    Actions::make([
                        Action::make('surat_pernyataan')
                            ->label('Surat Pernyataan Orangtua')
                            ->icon('heroicon-m-clipboard-document-list') 
                            ->color('gray')                           
                            ->url(function(Aktif $record) {
                                return Storage::url($record->surat_pernyataan);
                            })
                            ->openUrlInNewTab(),
                        Action::make('slip_bebasspp')
                            ->label('Slip Bebas Spp')
                            ->icon('heroicon-m-clipboard-document-list')                            
                            ->color('gray')
                            ->url(function(Aktif $record) {
                                return Storage::url($record->slip_bebasspp);
                            })
                            ->openUrlInNewTab(),
                        Action::make('memo_perpus')
                            ->label('Memo Perpus')
                            ->icon('heroicon-m-clipboard-document-list')     
                            ->color('gray')                       
                            ->url(function(Aktif $record) {
                                return Storage::url($record->memo_perpus);
                            })
                            ->openUrlInNewTab(),
                        // Action::make('slip_bebasspp')
                        //     ->icon('heroicon-m-clipboard-document-list')                                                        
                        //     ->url(function(Aktif $record) {
                        //         return url('report/aktif/'.$record->surat_id);
                        //     })
                        //     ->openUrlInNewTab(),
                    ])
                    ->label('Files')                    
                ]);
        }

    public static function getEloquentQuery(): Builder 
    {
        if (auth()->user()->roles->pluck('name')[0] === 'Dosen') {
            return Aktif::whereHas('surat', function($q) {
                $q->where('status', '!=', 'Baru');
                $q->where('status', '!=', 'Perbaikan');
                $q->where('status', '!=', 'Diperbaiki');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Kaprodi') {
            return Aktif::whereHas('surat', function($q) {
                $q->where('status', '!=', 'Baru');
                $q->where('status', '!=', 'Verifikasi');
                $q->where('status', '!=', 'Perbaikan');
                $q->where('status', '!=', 'Diperbaiki');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Dekan') {
            return Aktif::whereHas('surat', function($q) {
                $q->where('status', '!=', 'Baru');
                $q->where('status', '!=', 'Verifikasi');
                $q->where('status', '!=', 'Validasi Dosen');
                $q->where('status', '!=', 'Perbaikan');
                $q->where('status', '!=', 'Diperbaiki');
            });
            
        } else if (auth()->user()->roles->pluck('name')[0] === 'Wakil Rektor') {
            return Aktif::whereHas('surat', function($q) {
                $q->where('status', '!=', 'Baru');
                $q->where('status', '!=', 'Verifikasi');
                $q->where('status', '!=', 'Validasi Dosen');
                $q->where('status', '!=', 'Validasi Kaprodi');
                $q->where('status', '!=', 'Perbaikan');
                $q->where('status', '!=', 'Diperbaiki');
            });
        } else {
            return Aktif::whereHas('surat');
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAktifs::route('/'),
            // 'create' => Pages\CreateAktif::route('/create'),
            // 'edit' => Pages\EditAktif::route('/{record}/edit'),
        ];
    }
}
