<?php

namespace App\Filament\Resources\Data;

use App\Filament\Resources\Data\ProgramResource\Pages;
use App\Filament\Resources\Data\ProgramResource\RelationManagers;
use App\Models\Data\Program;
use App\Models\Data\Faculty;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProgramResource extends Resource
{
    protected static ?string $model = Program::class;

    protected static ?string $pluralModelLabel = 'Program Studi';

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';        

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label(__('Kode Program Studi'))
                    ->rules(function ($record) {
                        $code = $record?->code;
                        return $code
                            ? ['unique:program_data,code,' . $code]
                            : ['unique:program_data,code'];
                    })
                    ->columnSpan([
                        'sm'    => 2,
                        'lg'    => 1,
                        'xl'    => 1,
                        '2xl'   => 1
                    ]),
                Forms\Components\Select::make('faculty_data_id')
                    ->label(__('Group Fakultas'))
                    ->searchable()
                    ->options(Faculty::get()->pluck('name', 'id'))
                    ->columnSpan([
                        'sm'    => 2,
                        'lg'    => 1,
                        'xl'    => 1,
                        '2xl'   => 1
                    ]),
                Forms\Components\TextInput::make('program_name')
                    ->label(__('Nama Program Studi'))  
                    ->columnSpan([
                        'sm'    => 2,
                        'lg'    => 2,
                        'xl'    => 2,
                        '2xl'   => 2
                    ])                  
            ]);            
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
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

    public static function getNavigatioNGroup(): ?string
    {
        return __("Data");
    }    

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPrograms::route('/'),            
        ];
    }
}
