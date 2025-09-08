<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProgramaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nome' => $this->nome,
            'descricao' => $this->descricao,
            'data_inicio' => $this->data_inicio->format('Y-m-d'),
            'data_fim' => $this->data_fim->format('Y-m-d'),
            'estado' => $this->estado,
            'candidatos_count' => $this->candidatos()->count(),
            'is_active' => $this->estado === 'activo' && 
                          $this->data_inicio <= now() && 
                          now() <= $this->data_fim,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
