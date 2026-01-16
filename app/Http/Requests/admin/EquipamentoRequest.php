<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class EquipamentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // O código abaixo garante que o número de série seja único, 
        // mas permite salvar ao editar o mesmo item.
        $id = $this->route('equipamento') ? $this->route('equipamento')->id : null;

        return [
            'nome'         => 'required|string|max:255',
            'tipo'         => 'required|string|max:100',
            'numero_serie' => 'required|string|unique:equipamentos,numero_serie,' . $id,
            'empresa'      => 'required|string|max:255',
            'status'       => 'required|in:disponivel,em_uso,manutencao,descartado',
            'observacoes'  => 'nullable|string',
        ];
    }
}