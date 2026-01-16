<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EquipamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Protegido pelo middleware auth na rota
    }

    public function rules(): array
    {
        return [
            'nome'         => 'required|string|max:255',
            'tipo'         => 'required|string|max:100',
            'numero_serie' => 'required|string|unique:equipamentos,numero_serie,' . ($this->equipamento->id ?? 'NULL'),
            'empresa'      => 'required|string|max:255',
            'status'       => 'required|in:disponivel,em_uso,manutencao,descartado',
            'observacoes'  => 'nullable|string',
        ];
    }
}