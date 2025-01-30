<?php

namespace App\Models\Data;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    protected $table = 'faculty_data';    
    protected $fillable = [
        'code',
        'faculty_name',        
    ];

    public function getNameAttribute()
    {
        return "{$this->code} - {$this->faculty_name}";
    }
}
