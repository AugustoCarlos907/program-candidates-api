<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Candidatura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'candidato_id',
        'programa_id',
        'estado'
    ];

   public function candidato()
   {
        return $this->belongsTo(Candidato::class);
   }
    public function programa()
    {
        return $this->belongsTo(Programa::class);
    }
}
