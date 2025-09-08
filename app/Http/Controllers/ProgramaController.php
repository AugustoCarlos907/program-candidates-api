<?php

namespace App\Http\Controllers;

use App\Models\Programa;
use App\Http\Resources\ProgramaResource;
use App\Http\Requests\StoreProgramaRequest;
use App\Http\Requests\UpdateProgramaRequest;
use Illuminate\Http\Request;

class ProgramaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Programa::query();

        if ($request->filled('nome')) {
            $query->where('nome', 'ILIKE', '%' . $request->nome . '%');
        }

        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->boolean('ativos')) {
            $hoje = now()->toDateString();
            $query->where('estado', 'activo')
                  ->where('data_inicio', '<=', $hoje)
                  ->where('data_fim', '>=', $hoje);
        }

        $programas = $query->paginate($request->get('per_page', 15));

        return ProgramaResource::collection($programas);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProgramaRequest $request)
    {
        $programa = Programa::create($request->validated());

        return response()->json([
            'message' => 'Programa criado com sucesso!',
            'data' => new ProgramaResource($programa)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Programa $programa)
    {
        return new ProgramaResource($programa);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProgramaRequest $request, Programa $programa)
    {
        $programa->update($request->validated());

        return response()->json([
            'message' => 'Programa atualizado com sucesso!',
            'data' => new ProgramaResource($programa)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Programa $programa)
    {
        $programa->delete();

        return response()->json([
            'message' => 'Programa removido com sucesso!'
        ]);
    }
}
