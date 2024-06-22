<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FakultasResource\Pages;
use App\Filament\Resources\FakultasResource\RelationManagers;
use App\Models\Data\Fakultas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class FakultasResource extends Resource
{
    protected static ?string $model = Fakultas::class;

    protected static ?string $navigationGroup = 'Data';

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                
                Forms\Components\TextInput::make('code')
                    ->label('Kode Fakultas')
                    ->required()
                    ->maxLength(5),
                Forms\Components\TextInput::make('nama_fakultas')
                    ->label('Nama Fakultas')
                    ->required()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Fakultas')
                    // ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_fakultas')
                    ->label('Nama Fakultas')
                    ->searchable()
            ])->defaultSort('code', 'asc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hiddenLabel()->tooltip('Edit'),
                Tables\Actions\DeleteAction::make()->hiddenLabel()->tooltip('Delete')
                ->before(function (Tables\Actions\DeleteAction $action, Fakultas $record) {
                    if ($record->prodi()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Failed to delete!')
                            ->body('Fakultas ini sudah digunakan')
                            ->persistent()
                            ->send();
             
                            // This will halt and cancel the delete action modal.
                            $action->cancel();
                    }
                }),
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
            'index' => Pages\ListFakultas::route('/'),
            // 'create' => Pages\CreateFakultas::route('/create'),
            // 'edit' => Pages\EditFakultas::route('/{record}/edit'),
        ];
    }
}
