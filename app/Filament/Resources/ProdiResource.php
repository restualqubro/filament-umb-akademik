<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProdiResource\Pages;
use App\Filament\Resources\ProdiResource\RelationManagers;
use App\Models\Data\Fakultas;
use App\Models\Data\Prodi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProdiResource extends Resource
{
    protected static ?string $model = Prodi::class;

    protected static ?string $pluralModelLabel = 'Program Studi';

    protected static ?String $slug = 'prodi';

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Prodi')
                    ->required()
                    ->maxLength(5),
                Forms\Components\TextInput::make('nama_prodi')
                    ->label('Nama Prodi')
                    ->required(),
                Forms\Components\Select::make('fakultas_id')
                    ->label('Fakultas')
                    ->searchable()
                    ->options(Fakultas::all()->pluck('nama_fakultas', 'id'))
                    ->required()
            ])->columns('lg');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Prodi')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_prodi')
                    ->label('Nama Prodi')
                    ->searchable(),
                Tables\Columns\TextColumn::make('fakultas.nama_fakultas')
                    ->label('Fakultas')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete'),
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
            'index' => Pages\ListProdis::route('/'),
            // 'create' => Pages\CreateProdi::route('/create'),
            // 'edit' => Pages\EditProdi::route('/{record}/edit'),
        ];
    }
}
