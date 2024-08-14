<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\SuratResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Data\TahunAkademik;
use Illuminate\Database\Eloquent\Builder;

class SuratWidget extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
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
                    ->options(TahunAkademik::all()->pluck('code', 'id'))                    
            ])
            ->actions([
                Tables\Actions\ViewAction::make('detail'),                                
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
