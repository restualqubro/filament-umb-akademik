<?php

namespace App\Models\Layanan;

use App\Models\Surat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class Cuti extends Model
{
    use HasFactory, HasRoles;    

    protected $table = 'surat_cuti';
    protected $fillable = [
        'surat_id',
        'alasan',
        'surat_pernyataan', 
        'slip_bebasspp',
        'memo_perpus',
        'dosen_id',
        'admin_id', 
        'wrektor_id', 
        'no_surat'
    ];

    public function surat(): BelongsTo
    {
        return $this->belongsTo(Surat::class);
    }
    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function wrektor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function getRoleAttribute()
    {    
        return $this->roles()->first();
    }    
}
