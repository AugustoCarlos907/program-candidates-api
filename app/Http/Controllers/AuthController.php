<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\CandidatoResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $candidato = $user->candidato()->create([
            'user_id' => $user->id,
            'genero' => $request->genero,
            'telefone' => $request->telefone,
            'data_nascimento' => $request->data_nascimento,
            'nacionalidade' => $request->nacionalidade,
            'endereco' => $request->endereco,
        ]);

        $candidato->load('user');

        return response()->json([
            'message' => 'Usuário e candidato criados com sucesso!',
            'data' => new CandidatoResource($candidato)
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = Auth::user();
        $candidato = $user->candidato;

        if (!$candidato) {
            $candidato = $user->candidato()->create([
                'user_id' => $user->id
            ]);
        }

        $candidato->load('user');
        $token = $user->createToken('token-api')->plainTextToken;

        return response()->json([
            'message' => 'Login realizado com sucesso',
            'token' => $token,
            'data' => new CandidatoResource($candidato)
        ]);
    }

    public function logout(Request $request )
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
