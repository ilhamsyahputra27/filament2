<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    public function Jurusan(): BelongsTo
    {
        return $this->belongsTo(Jurusan ::class);
    }
}
