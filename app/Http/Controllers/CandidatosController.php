<?php

namespace App\Http\Controllers;

use App\Models\Candidato;
use App\Models\User;
use App\Http\Resources\CandidatoResource;
use Illuminate\Http\Request;

class CandidatosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
    {
        $query = Candidato::with('user');

        if ($request->filled('name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'ILIKE', '%' . $request->name . '%');
            });
        }

        if ($request->filled('email')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('email', 'ILIKE', '%' . $request->email . '%');
            });
        }

        $candidatos = $query->paginate($request->get('per_page', 15));

        return CandidatoResource::collection($candidatos);
    }

    public function show(Candidato $candidato)
    {
        $candidato->load('user');
        return new CandidatoResource($candidato);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidato $candidato)
    {
        $user = $candidato->user;

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',      // minúscula
                'regex:/[A-Z]/',      // maiúscula
                'regex:/[0-9]/',      // número
            ],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Candidato atualizado com sucesso',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Candidato $candidato)
    {
        if ($candidato->user) {
            $candidato->user->delete();
        }

        if ($candidato->exists) {
            $candidato->delete();
        }

        return response()->json(['message' => 'Candidato apagado com sucesso']);
    }
}
