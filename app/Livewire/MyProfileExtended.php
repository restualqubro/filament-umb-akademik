<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Data\Prodi;
use App\Models\Data\Fakultas;
use Illuminate\Support\Facades\DB;
use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;

use function Filament\Support\is_app_url;

class MyProfileExtended extends MyProfileComponent
{
    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public $user;

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->form->fill($data);
    }

    public function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            SpatieMediaLibraryFileUpload::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->avatar(),
                Grid::make()->schema([
                    TextInput::make('username')
                        ->disabled()
                        ->required(),
                    TextInput::make('email')
                        ->disabled()
                        ->required(),
                ]),
                Grid::make()->schema([
                    TextInput::make('firstname')
                        ->label('Nama depan')
                        ->required(),
                    TextInput::make('lastname')
                        ->label('Nama Belakang')                            
                ]),
                Grid::make()->schema([
                    DatePicker::make('birth_date')
                        ->label('Tanggal Lahir')
                        ->required(),
                    TextInput::make('birth_place')
                        ->label('Tempat Lahir')
                        ->required()
                ]),                 
                Grid::make()->schema([
                    Select::make('gender')
                        ->options([
                            'Laki-laki' => 'Laki-laki',
                            'Perempuan' => 'perempuan'
                        ])
                        ->label('Jenis Kelamin'),
                    Select::make('agama')
                        ->options([
                            'Islam'     => 'Islam',
                            'Kristen'   => 'Kristen',
                            'Katolik'   => 'Katolik', 
                            'Hindu'     => 'Hindu',
                            'Budha'     => 'Budha',
                            'Kong Hu Cu'=> 'Kong Hu Cu'
                        ])
                        ->label('Agama')
                ]),
                Grid::make()->schema([
                    TextInput::make('telp')                            
                        ->label('HP / Wa Aktif')
                        ->tel(),
                    TextArea::make('address')
                        ->label('Alamat')
                ]),
                Group::make()
                    ->relationship('mahasiswa')                        
                    ->schema([
                        Grid::make()->schema([
                            TextInput::make('nik')
                            ->label('NIK / Nomor Induk Kependudukan'),
                            TextInput::make('pddikti')
                                ->label('Link PDDIKTI Mahasiswa'),
                        ]),
                        Grid::make()->schema([
                            Select::make('dosen_id')
                                ->preload()
                                ->searchable()
                                ->options(User::select('id', DB::raw("CONCAT(users.firstname,' ',users.lastname) as full_name"))->with('roles')->WhereRelation('roles', 'name', '=', 'dosen')->pluck('full_name', 'id')),                                    
                            Select::make('prodi_id')
                                ->searchable()
                                ->options(Prodi::all()->pluck('nama_prodi', 'id'))
                        ]),
                        // TextInput::make('isComplete')    
                            // ->reactive()            
                            // ->live()
                            // ->afterStateUpdated(function(Get $get, Set $set) {
                            //     $get('nik');
                            //     $get('pddikti');
                            //     $get('dosen_id');
                            //     $get('prodi_id');
                            //     $set('isComplete', FALSE);
                            // })                                                
                    ])
                    ->saveRelationshipsUsing(static function ($component, $state) {                        
                        $relationship = $component->getRelationship();
                        if($state['nik'] != null &&
                            $state['pddikti'] != null &&
                            $state['dosen_id'] != null &&
                            $state['prodi_id'] != null
                            )
                            {
                                $status = TRUE;
                            } else {
                                $status = FALSE;
                            }                     
                        $newState = [
                            'nik'       => $state['nik'],
                            'pddikti'   => $state['pddikti'],
                            'dosen_id'  => $state['dosen_id'],
                            'prodi_id'  => $state['prodi_id'],
                            'isComplete'=> $status,
                        ];
                        // $relationship->sycn($newState);
                        // dd($newState);
                        $relationship->where('user_id', auth()->user()->id)->update($newState); //associate, attach, etc..'
                        // $record->mahasiswa()->saveMany($newState);
                    })
                    ->visible(fn() => auth()->user()->roles->pluck('name')->first() === 'Mahasiswa'),
                Group::make()
                    ->relationship('pegawai')
                    ->schema([
                        Select::make('prodi_id')
                        ->options(Prodi::all()->pluck('nama_prodi', 'id')),
                        Hidden::make('isComplete')
                            ->default(function(Get $get, Set $set) {
                                if (                                        
                                    $get('prodi_id') != null
                                    ) {
                                        return TRUE;
                                    } else {
                                        return FALSE;
                                    }
                            }),
                    ])->visible(fn() => auth()->user()->roles->pluck('name')->first() === 'Kaprodi'),
                Group::make()
                    ->relationship('pegawai')
                    ->schema([
                        Select::make('fakultas_id')
                        ->options(Fakultas::all()->pluck('nama_fakultas', 'id')),
                        Hidden::make('isComplete')
                            ->default(function(Get $get, Set $set) {
                                if (                                        
                                    $get('fakultas_id') != null
                                    ) {
                                        return TRUE;
                                    } else {
                                        return FALSE;
                                    }
                            }),
                    ])->visible(fn() => auth()->user()->roles->pluck('name')->first() === 'Dekan'),
                ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data');
    }

    public function submit()
    {
        try {
            $data = $this->form->getState();

            $this->handleRecordUpdate($this->getUser(), $data);

            Notification::make()
                ->title('Profile updated')
                ->success()
                ->send();

            $this->redirect('my-profile', navigate: FilamentView::hasSpaMode() && is_app_url('my-profile'));
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Failed to update.')
                ->danger()
                ->send();
        }
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (
            $data['firstname'] != null &&
            $data['lastname'] != null &&
            $data['birth_date'] != null &&
            $data['birth_place'] != null &&
            $data['gender'] != null &&
            $data['agama'] != null &&
            $data['telp'] != null &&
            $data['address'] != null
        ) {
            $data['isComplete'] = TRUE;
        } else {
            $data['isComplete'] = FALSE;
        }
        $record->update($data);        
        return $record;
    }

    public function render(): View
    {
        return view("livewire.my-profile-extended");
    }
}
