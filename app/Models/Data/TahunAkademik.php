<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{   
    use HasFactory, HasUlids;

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
}
