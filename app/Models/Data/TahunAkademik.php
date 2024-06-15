<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAkademik extends Model
{   
    use HasFactory, HasUuids;

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
