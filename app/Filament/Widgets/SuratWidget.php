<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SuratResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use App\Models\Data\TahunAkademik;
use Illuminate\Database\Eloquent\Builder;

class SuratWidget extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $tahunakademik = TahunAkademik::get();
        return $table
            ->query(
                SuratResource::getEloquentQuery()
            )
            ->modifyQueryUsing(function (Builder $query) {                                
                return $query->where('mahasiswa_id', auth()->user()->id);                
            })
            ->columns([                
                Tables\Columns\TextColumn::make('jenis')                    
                    ->label('JENIS')
                    ->badge(),                
                Tables\Columns\TextColumn::make('akademik.tahun')
                    ->label('TAHUN'),
                Tables\Columns\TextColumn::make('akademik.semester')
                    ->label('SEMESTER'),
                Tables\Columns\TextColumn::make('status')
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
                    ->label('Tahun Akademik')
                    ->options(                                                
                        $tahunakademik->mapWithKeys(function (TahunAkademik $tahunakademik) {
                            return [$tahunakademik->code => sprintf('%s %s', $tahunakademik->tahun, $tahunakademik->semester)];
                        })
                        ) 
            ])
            ->actions([
                Tables\Actions\Action::make('detail')
                    ->label('Details')
                    ->icon('heroicon-o-eye')
                    ->color('grey')   
                    ->infolist([
                        
                        Section::make('Biodata Mahasiswa')                            
                            ->schema([
                                TextEntry::make('mahasiswa.username')
                                ->label('NPM Mahasiswa'),
                                TextEntry::make('mahasiswa.name')
                                    ->label('Nama Mahasiswa'), 
                                TextEntry::make('mahasiswa.mahasiswa.prodi.nama_prodi')
                                    ->label('Program Studi'),
                                TextEntry::make('akademik.name')
                                    ->label('Semester'),
                                TextEntry::make('status')
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
                            ]),
                        Section::make('Data Pengajuan')                            
                            ->schema([
                                TextEntry::make('cuti.alasan')
                                    ->label('Alasan Pengajuan'),
                                TextEntry::make('updated_at')
                                    ->label('Timestamp Update Terakhir'),
                                TextEntry::make('surat.update_detail')
                                    ->label('Detail Update'),                                
                            ]),                                                                                                                           
                    ]),                                
                Tables\Actions\Action::make('print')                              
                    ->icon('heroicon-o-printer')
                    ->color('success')                    
                    ->url(function($record) {
                        return url('report/cuti/'.$record->id);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->status === 'Disetujui'),                      
            ]);
    }    

    public static function canView(): bool
    {
        return auth()->user()->roles->pluck('name')[0] === 'Mahasiswa';
    }
}
