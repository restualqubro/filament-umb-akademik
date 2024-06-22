<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pegawai extends Model
{
    use HasFactory;

    protected $table = 'pegawai'; 
    protected $fillable = [
        'user_id',
        'prodi_id',        
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }    

    public function dosen(): BelongsTo 
    {
        return $this->belongsTo(User::class);
    }
}
