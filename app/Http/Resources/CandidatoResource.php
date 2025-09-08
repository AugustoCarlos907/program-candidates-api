<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CandidatoResource extends JsonResource
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
            'user_id' => $this->user_id,
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'created_at' => $this->user->created_at,
            ],
            'genero' => $this->genero,
            'telefone' => $this->telefone,
            'data_nascimento' => $this->data_nascimento?->format('Y-m-d'),
            'idade' => $this->data_nascimento ? $this->data_nascimento->age : null,
            'nacionalidade' => $this->nacionalidade,
            'endereco' => $this->endereco,
            'programas_count' => $this->programas()->count(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
