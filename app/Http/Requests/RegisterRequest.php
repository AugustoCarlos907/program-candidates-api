<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|min:2',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:255',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
            ],
            // Campos opcionais do candidato
            'genero' => 'nullable|in:M,F',
            'telefone' => 'nullable|string|max:20',
            'data_nascimento' => 'nullable|date|before:today',
            'nacionalidade' => 'nullable|string|max:100',
            'endereco' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'O email deve ter um formato válido.',
            'email.unique' => 'Este email já está registado.',
            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',
            'password.regex' => 'A senha deve conter pelo menos: 1 letra minúscula, 1 maiúscula, 1 número e 1 símbolo especial.',
            'genero.in' => 'O gênero deve ser M (Masculino) ou F (Feminino).',
            'data_nascimento.date' => 'A data de nascimento deve ser uma data válida.',
            'data_nascimento.before' => 'A data de nascimento deve ser anterior a hoje.',
            'telefone.max' => 'O telefone deve ter no máximo 20 caracteres.',
            'nacionalidade.max' => 'A nacionalidade deve ter no máximo 100 caracteres.',
            'endereco.max' => 'O endereço deve ter no máximo 255 caracteres.',
        ];
    }
}
