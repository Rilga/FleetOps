<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ship extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'image',
        'imo_number',
    ];

    // Relasi: Satu kapal punya banyak mesin (untuk langkah selanjutnya)
    public function machineries()
    {
        return $this->hasMany(Machinery::class);
    }
}
