<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TahunAkademikResource\Pages;
use App\Filament\Resources\TahunAkademikResource\RelationManagers;
use App\Models\Data\TahunAkademik;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TahunAkademikResource extends Resource
{
    protected static ?string $model = TahunAkademik::class;

    protected static ?string $pluralModelLabel = 'TahunAkademik';

    protected static ?string $slug = 'tahunakademik';

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
                Forms\Components\TextInput::make('tahun')
                    ->id('tahun')
                    ->label('Tahun Akademik')
                    ->required()
                    ->numeric()
                    ->maxLength(4),         
                Forms\Components\Select::make('semester')
                    ->id('semester')
                    ->label('Semester')
                    ->required()
                    ->options([
                        'GANJIL'    => 'GANJIL',
                        'GENAP'     => 'GENAP'                    
                    ]),                               
                Forms\Components\TextInput::make('code')
                    ->unique()
                    ->required()
                    ->label('Kode Akademik'),
                Forms\Components\Actions::make([
                    Forms\Components\Actions\Action::make('generate')
                        ->label('Generate Kode')
                        ->icon('heroicon-o-arrow-path')
                        ->action(function (Forms\Get $get, Forms\Set $set) {
                            $tahun = $get('tahun');
                            $semester = $get('semester');
                            if ($tahun != null || $semester != null)
                            {
                                if ($get('semester') == 'GANJIL') {
                                    $number = '1';
                                } else if ($get('semester') == 'GENAP') {
                                    $number = '2';
                                } else {
                                    $number = null;
                                }
                                $set('code', "AK".str($get('tahun')).$number);
                            } else {
                                $set('code', 'Generate Kode Gagal!!');
                            }
                            
                        })
                ]),                     
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([                
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Akademik')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tahun Akademik')                    
                    ->searchable(),
                Tables\Columns\TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'GANJIL'    => 'warning',
                        'GENAP'     => 'success'
                    }),                
            ])->defaultSort('id', 'ASC')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTahunAkademiks::route('/'),
            // 'create' => Pages\CreateTahunAkademik::route('/create'),
            // 'edit' => Pages\EditTahunAkademik::route('/{record}/edit'),
        ];
    }
}
