<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class Programa extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
        'nome',
        'estado',
        'descricao',
        'data_inicio',
        'data_fim'
    ];

   protected function casts(): array
   {
       return [
           'data_inicio' => 'date',
           'data_fim' => 'date',
       ];
   }

   public function candidatos()
    {
        return $this->belongsToMany(Candidato::class, 'candidaturas')
                    ->withTimestamps();
    }
}
