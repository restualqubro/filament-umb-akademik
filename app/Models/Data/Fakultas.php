<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class Fakultas extends Model
{
    use HasFactory, HasUlids, HasRoles;

    protected $table = 'fakultas';
    protected $primaryKey = 'id';
    protected $cast = ['id' => 'string'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = 
    [         
        'code',
        'nama_fakultas'
    ];

    public function prodi(): HasMany
    {
        return $this->hasMany(Prodi::class);
    }
}
