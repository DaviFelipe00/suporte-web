<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipamento;
use App\Http\Requests\Admin\EquipamentoRequest;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $itens = Equipamento::latest()->paginate(10);
        return view('admin.inventario.index', compact('itens'));
    }

    public function store(EquipamentoRequest $request)
    {
        Equipamento::create($request->validated());
        return back()->with('sucesso', 'Equipamento registrado!');
    }

    public function update(EquipamentoRequest $request, Equipamento $equipamento)
    {
        $equipamento->update($request->validated());
        return back()->with('sucesso', 'Item atualizado com sucesso!');
    }

    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
        return back()->with('sucesso', 'Equipamento removido.');
    }
}