<?php

namespace App\Models;

use App\Models\Data\TahunAkademik;
use App\Models\Layanan\Cuti;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Surat extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'surat';
    protected $fillable = [
        'id',
        'mahasiswa_id',
        'akademik_id', 
        'operator_id',
        'update_detail',
        'status',
        'jenis',        
    ];

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mahasiswa_id', 'id');
    }

    public function operator(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }    

    public function cuti(): HasOne
    {
        return $this->hasOne(Cuti::class);
    }
    
    public function akademik(): BelongsTo
    {
        return $this->belongsTo(TahunAkademik::class, 'akademik_id', 'code');
    }    
}
