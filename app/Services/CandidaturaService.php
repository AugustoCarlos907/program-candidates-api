<?php

namespace App\Services;

use App\Models\Candidato;
use App\Models\Candidatura;
use App\Models\Programa;
use Exception;

class CandidaturaService
{
    /**
     * Cria uma nova candidatura com validações de regras de negócio
     */
    public function criarCandidatura(int $candidatoId, int $programaId, ?string $estado = 'pendente'): Candidatura
    {
        $candidato = Candidato::find($candidatoId);
        $programa = Programa::find($programaId);

        if (!$candidato || !$programa) {
            throw new Exception('Candidato ou Programa não encontrado');
        }

        // Verificar se já existe candidatura
        $candidaturaExistente = Candidatura::where('candidato_id', $candidatoId)
            ->where('programa_id', $programaId)
            ->exists();

        if ($candidaturaExistente) {
            throw new Exception('Já existe uma candidatura para este programa');
        }

        // Verificar se o programa está ativo
        if ($programa->estado !== 'activo') {
            throw new Exception('O programa não está ativo');
        }

        // Verificar se o programa está no intervalo permitido
        $hoje = now()->toDateString();
        if (!($programa->data_inicio <= $hoje && $hoje <= $programa->data_fim)) {
            throw new Exception('O programa não está no intervalo de datas correcto');
        }

        // Criar candidatura
        return Candidatura::create([
            'candidato_id' => $candidatoId,
            'programa_id' => $programaId,
            'estado' => $estado
        ]);
    }

    /**
     * Retorna programas elegíveis para candidatura (ativos e dentro do prazo)
     */
    public function obterProgramasElegiveis()
    {
        $hoje = now()->toDateString();
        
        return Programa::where('estado', 'activo')
                      ->where('data_inicio', '<=', $hoje)
                      ->where('data_fim', '>=', $hoje)
                      ->get();
    }
}