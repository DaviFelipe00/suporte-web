<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Equipamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'numero_serie',
        'empresa',
        'status',
        'observacoes'
    ];
}