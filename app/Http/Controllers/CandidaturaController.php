<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\Candidatura;
use App\Models\Programa;
use App\Http\Resources\CandidaturaResource;
use App\Http\Requests\StoreCandidaturaRequest;
use App\Services\CandidaturaService;
use Illuminate\Http\Request;
use Exception;

class CandidaturaController extends Controller
{
    protected $candidaturaService;

    public function __construct(CandidaturaService $candidaturaService)
    {
        $this->candidaturaService = $candidaturaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Candidatura::with(['candidato.user', 'programa']);

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('candidato_id')) {
            $query->where('candidato_id', $request->candidato_id);
        }

        if ($request->filled('programa_id')) {
            $query->where('programa_id', $request->programa_id);
        }

        $candidaturas = $query->paginate($request->get('per_page', 15));

        return CandidaturaResource::collection($candidaturas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCandidaturaRequest $request)
    {
        try {
            $candidatura = $this->candidaturaService->criarCandidatura(
                $request->candidato_id,
                $request->programa_id,
                $request->estado ?? 'pendente'
            );

            $candidatura->load(['candidato.user', 'programa']);

            return response()->json([
                'message' => 'Candidatura criada com sucesso',
                'data' => new CandidaturaResource($candidatura)
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Candidatura $candidatura)
    {
        $candidatura->load(['candidato.user', 'programa']);
        return new CandidaturaResource($candidatura);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidatura $candidatura)
    {
        $request->validate([
            'estado' => 'required|in:aprovado,pendente,reprovado'
        ]);

        $candidatura->update([
            'estado' => $request->estado
        ]);

        $candidatura->load(['candidato.user', 'programa']);

        return response()->json([
            'message' => 'Estado da candidatura atualizado com sucesso',
            'data' => new CandidaturaResource($candidatura)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidatura $candidatura)
    {
        $candidatura->delete();

        return response()->json([
            'message' => 'Candidatura removida com sucesso'
        ]);
    }
}
