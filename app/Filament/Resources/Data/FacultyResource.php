<?php

namespace App\Filament\Resources\Data;

use App\Filament\Resources\Data\FacultyResource\Pages;
use App\Filament\Resources\Data\FacultyResource\RelationManagers;
use App\Models\Data\Faculty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static ?string $pluralModelLabel = 'Fakultas';    

    protected static ?int $navigationSort = 1;    
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('Kode Fakultas'))
                    ->rules(function ($record) {
                        $code = $record?->code;
                        return $code 
                            ? ['unique:faculty_data,code,' . $code]
                            : ['unique:faculty_data,code'];
                    }),
                Forms\Components\TextInput::make('faculty_name')
                    ->label('Nama Fakultas')                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Fakultas')                    
                    ->sortable(),
                Tables\Columns\TextColumn::make('faculty_name')
                    ->label('Nama Fakultas')                    
                    ->sortable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('code', 'ASC');
    }

    public static function getNavigationGroup(): ?string
    {
        return __("Data");
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaculties::route('/'),            
        ];
    }
}
