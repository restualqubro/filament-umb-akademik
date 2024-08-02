<?php

namespace App\Filament\Resources\Pengajuan;

use App\Filament\Resources\Pengajuan\CutiResource\Pages;
use App\Filament\Resources\Pengajuan\CutiResource\RelationManagers;
use App\Models\Layanan\Cuti;
use App\Models\Surat;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;


class CutiResource extends Resource


{
    protected static ?string $model = Cuti::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                Tables\Columns\TextColumn::make('surat.id'),
                Tables\Columns\TextColumn::make('surat.jenis'),
                Tables\Columns\TextColumn::make('surat.status'),
                Tables\Columns\TextColumn::make('surat.mahasiswa.username')
            ])            
            ->filters([                
            ])
            ->actions([                
                Tables\Actions\Action::make('checked')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['operator_id'] = auth()->user()->id;
                        $record['status'] = "Checked";
                        Surat::where('id', $surat['surat_id'])->update([
                            'operator_id'   => $record['operator_id'],
                            'status'        => $record['status'],                            
                        ]);
                    })->visible(fn(Cuti $record) => $record->surat->status === 'Baru' && auth()->user()->roles->pluck('name')[0] === 'Operator'),
                Tables\Actions\Action::make('verifikasi')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('secondary')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['dosen_id'] = auth()->user()->id;
                        $record['status'] = "Verifikasi";
                        Surat::where('id', $surat['surat_id'])->update([                            
                            'status'        => $record['status'],                            
                        ]);
                        Cuti::where('id', $surat['id'])->update([
                            'dosen_id'      => $record['dosen_id']
                        ]);
                    })->visible(fn (Cuti $record) => $record->surat->status === 'Checked' && auth()->user()->roles->pluck('name')[0] === 'Dosen'),
                Tables\Actions\Action::make('validasi')
                    ->label('Validasi')
                    ->icon('heroicon-o-list-bullet')
                    ->color('info')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['admin_id'] = auth()->user()->id;
                        $record['status'] = "Validasi";
                        Surat::where('id', $surat['surat_id'])->update([                            
                            'status'        => $record['status'],                            
                        ]);
                        Cuti::where('id', $surat['id'])->update([
                            'admin_id'      => $record['admin_id']
                        ]);
                    })->visible(fn (Cuti $record) => $record->surat->status === 'Verifikasi' && auth()->user()->roles->pluck('name')[0] === 'Admin'),
                Tables\Actions\Action::make('setuju')
                    ->label('Setuju')
                    ->icon('heroicon-o-list-bullet')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (array $data, Cuti $surat): void {                                                                        
                        $record[] = array();                        
                        $record['wrektor_id'] = auth()->user()->id;
                        $no_surat = 'UMB/SCT/VIII/2024/001';
                        $record['status'] = "Disetujui";
                        Surat::where('id', $surat['surat_id'])->update([                            
                            'status'        => $record['status'],                            
                        ]);
                        Cuti::where('id', $surat['id'])->update([
                            'wrektor_id'    => $record['wrektor_id'],
                            'no_surat'      => $no_surat,                            
                        ]);
                    })->visible(fn (Cuti $record) => $record->surat->status === 'Validasi' && auth()->user()->roles->pluck('name')[0] === 'Wakil Rektor')                ,
                Tables\Actions\Action::make('print')
                    ->label('Print')
                    ->icon('heroicon-o-printer')
                    ->color('danger')                    
                    ->url(function(Cuti $record) {
                        return url('report/cuti/'.$record->surat_id);
                    })
                    ->openUrlInNewTab()                                                                                                
                    ->visible(fn (Cuti $record) => $record->surat->status === 'Disetujui' && auth()->user()->roles->pluck('name')[0] === 'Mahasiswa')                
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder 
    {
        if (auth()->user()->roles->pluck('name')[0] === 'Dosen') {
            return Cuti::whereHas('surat', function($q) {
                $q->where('status', 'Checked');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Admin') {
            return Cuti::whereHas('surat', function($q) {
                $q->where('status', 'Verifikasi');
            });
        } else if (auth()->user()->roles->pluck('name')[0] === 'Wakil Rektor') {
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
