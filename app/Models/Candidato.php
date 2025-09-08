<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidato extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'genero',
        'telefone',
        'data_nascimento',
        'nacionalidade',
        'endereco'
    ];

    protected function casts(): array
    {
        return [
            'data_nascimento' => 'date',
        ];
    }

    public function programas()
    {
      return $this->belongsToMany(Programa::class, 'candidaturas')
                  ->withTimestamps();
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
