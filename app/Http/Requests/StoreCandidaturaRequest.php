<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCandidaturaRequest extends FormRequest
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
            'candidato_id' => [
                'required',
                'integer',
                'exists:candidatos,id'
            ],
            'programa_id' => [
                'required', 
                'integer',
                'exists:programas,id'
            ],
            'estado' => [
                'nullable',
                'string',
                'in:aprovado,pendente,reprovado'
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'candidato_id.required' => 'O ID do candidato é obrigatório.',
            'candidato_id.exists' => 'Candidato não encontrado.',
            'programa_id.required' => 'O ID do programa é obrigatório.',
            'programa_id.exists' => 'Programa não encontrado.',
            'estado.in' => 'O estado deve ser: aprovado, pendente ou reprovado.',
        ];
    }
}
