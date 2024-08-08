<?php

namespace App\Filament\Resources\Pengajuan;

use App\Filament\Resources\Pengajuan\CutiResource\Pages;
use App\Filament\Resources\Pengajuan\CutiResource\RelationManagers;
use App\Models\Layanan\Cuti;
use App\Models\Surat;
use App\Models\Data\TahunAkademik;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Support\Enums\VerticalAlignment;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class CutiResource extends Resource


{
    protected static ?string $model = Cuti::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $pluralModelLabel = 'Pengajuan Cuti';

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
                Tables\Columns\TextColumn::make('surat.jenis')
                    ->label('JENIS SURAT'),
                Tables\Columns\TextColumn::make('surat.status')
                    ->label('STATUS SURAT')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Baru' => 'gray',
                        'Verifikasi' => 'warning',
                        'Validasi Dosen' => 'secondary',
                        'Validasi Kaprodi' => 'secondary',
                        'Disetujui' => 'success',
                        'Ditolak' => 'danger',
                        'Perbaikan' => 'info'                            
                    }),                
            ])            
            ->filters([
                Tables\Filters\SelectFilter::make('akademik_id')                                        
                    ->relationship(name: 'surat', titleAttribute: 'akademik_id')            
            ])
            ->actions([
                Tables\Actions\ViewAction::make('detail'),                
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['operator_id'] = auth()->user()->id;
                        $record['status'] = "Verifikasi";
                        Surat::where('id', $surat['surat_id'])->update([
                            'operator_id'   => $record['operator_id'],
                            'update_detail' => 'Pengajuan anda telah mendapatkan Verifikasi dari Operator',
                            'status'        => $record['status'],                            
                        ]);
                    })->visible(fn(Cuti $record) => $record->surat->status === 'Baru' && auth()->user()->roles->pluck('name')[0] === 'Operator'),
                Tables\Actions\Action::make('validasi_dosen')
                    ->label('Validasi Dosen')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['dosen_id'] = auth()->user()->id;
                        $record['status'] = "Validasi Dosen";
                        Surat::where('id', $surat['surat_id'])->update([                            
                            'update_detail' => 'Pengajuan anda telah mendapatkan Validasi dari Dosen Pembimbing Akademik',
                            'status'        => $record['status'],                            
                        ]);
                        Cuti::where('id', $surat['id'])->update([
                            'dosen_id'      => $record['dosen_id']
                        ]);
                    })->visible(fn (Cuti $record) => $record->surat->status === 'Verifikasi' && auth()->user()->roles->pluck('name')[0] === 'Dosen'),
                Tables\Actions\Action::make('validasi_kaprodi')
                    ->label('Validasi Kaprodi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['kaprodi_id'] = auth()->user()->id;
                        $record['status'] = "Validasi Kaprodi";
                        Surat::where('id', $surat['surat_id'])->update([ 
                            'update_detail' => 'Pengajuan anda telah mendapatkan Validasi dari Kepala Program Studi',                           
                            'status'        => $record['status'],                            
                        ]);
                        Cuti::where('id', $surat['id'])->update([
                            'kaprodi_id'      => $record['kaprodi_id']
                        ]);
                    })->visible(fn (Cuti $record) => $record->surat->status === 'Validasi Dosen' && auth()->user()->roles->pluck('name')[0] === 'Kaprodi'),
                Tables\Actions\Action::make('disetujui')
                    ->label('Disetujui')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['dekan_id'] = auth()->user()->id;                        
                        $record['status'] = "Disetujui";
                        Surat::where('id', $surat['surat_id'])->update([  
                            'update_detail' => 'Pengajuan anda telah mendapatkan Persetujuan dari Dekan dan anda dapat menggunakan Surat Cuti ini sebagai Syarat Sah',                          
                            'status'        => $record['status'],                            
                        ]);
                        Cuti::where('id', $surat['id'])->update([
                            'dekan_id'    => $record['dekan_id'],                            
                        ]);
                    })->visible(fn (Cuti $record) => $record->surat->status === 'Validasi Kaprodi' && auth()->user()->roles->pluck('name')[0] === 'Dekan'),                
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
                            'Disetujui' => 'success',
                            'Ditolak' => 'danger',
                            'Perbaikan' => 'info'                            
                        }),
                    TextEntry::make('alasan')
                        ->label('Alasan Pengajuan'),
                    TextEntry::make('updated_at')
                        ->label('Timestamp Update Terakhir'),
                    TextEntry::make('update_detail')
                        ->label('Detail Update'),                   
                    Actions::make([
                        Action::make('surat_pernyataan')
                            ->label('Surat Pernyataan Orangtua')
                            ->icon('heroicon-m-clipboard-document-list') 
                            ->color('gray')                           
                            ->url(function(Cuti $record) {
                                return Storage::url($record->surat_pernyataan);
                            })
                            ->openUrlInNewTab(),
                        Action::make('slip_bebasspp')
                            ->label('Slip Bebas Spp')
                            ->icon('heroicon-m-clipboard-document-list')                            
                            ->color('gray')
                            ->url(function(Cuti $record) {
                                return Storage::url($record->slip_bebasspp);
                            })
                            ->openUrlInNewTab(),
                        Action::make('memo_perpus')
                            ->label('Memo Perpus')
                            ->icon('heroicon-m-clipboard-document-list')     
                            ->color('gray')                       
                            ->url(function(Cuti $record) {
                                return Storage::url($record->memo_perpus);
                            })
                            ->openUrlInNewTab(),
                        // Action::make('slip_bebasspp')
                        //     ->icon('heroicon-m-clipboard-document-list')                                                        
                        //     ->url(function(Cuti $record) {
                        //         return url('report/aktif/'.$record->surat_id);
                        //     })
                        //     ->openUrlInNewTab(),
                    ])
                    ->label('Files')
                    ->verticalAlignment(VerticalAlignment::End),
                ]);
        }

    public static function getEloquentQuery(): Builder 
    {
        if (auth()->user()->roles->pluck('name')[0] === 'Dosen') {
            return Cuti::whereHas('surat', function($q) {
                $q->where('status', 'Checked');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Kaprodi') {
            return Cuti::whereHas('surat', function($q) {
                $q->where('status', 'Verifikasi');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Dekan') {
            return Cuti::whereHas('surat', function($q) {
                $q->where('status', 'Validasi');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Mahasiswa') {
            return Cuti::whereHas('surat.mahasiswa', function($q) {
                $q->where('mahasiswa_id', auth()->user()->id);
            });
        } else {
            return Cuti::whereHas('surat');
        }
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCutis::route('/'),
            'create' => Pages\CreateCuti::route('/create'),
            'edit' => Pages\EditCuti::route('/{record}/edit'),
        ];
    }
}
