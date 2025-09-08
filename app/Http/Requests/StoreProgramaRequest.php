<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProgramaRequest extends FormRequest
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
            'nome' => 'required|string|max:255|min:2',
            'descricao' => 'required|string|max:1000',
            'estado' => 'required|in:activo,inactivo',
            'data_inicio' => 'required|date|after_or_equal:today',
            'data_fim' => 'required|date|after:data_inicio',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do programa é obrigatório.',
            'nome.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'descricao.required' => 'A descrição é obrigatória.',
            'descricao.max' => 'A descrição deve ter no máximo 1000 caracteres.',
            'estado.required' => 'O estado é obrigatório.',
            'estado.in' => 'O estado deve ser: activo ou inactivo.',
            'data_inicio.required' => 'A data de início é obrigatória.',
            'data_inicio.after_or_equal' => 'A data de início deve ser hoje ou no futuro.',
            'data_fim.required' => 'A data de fim é obrigatória.',
            'data_fim.after' => 'A data de fim deve ser posterior à data de início.',
        ];
    }
}
