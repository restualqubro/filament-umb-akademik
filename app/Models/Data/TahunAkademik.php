<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Traits\HasRoles;

class TahunAkademik extends Model
{   
    use HasFactory, HasUlids, HasRoles;

    protected $table = 'tahunakademik';
    protected $primaryKey = 'id';
    protected $cast = ['id' => 'string'];
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = 
    [         
        'code',
        'tahun',
        'semester'
    ];

    public function getNameAttribute() 
    {
        return "{$this->tahun} {$this->semester}";
    }
}
