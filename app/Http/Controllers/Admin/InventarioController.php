<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Equipamento;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $itens = Equipamento::latest()->paginate(15);
        return view('admin.inventario.index', compact('itens'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'tipo' => 'required|string',
            'numero_serie' => 'required|unique:equipamentos,numero_serie',
            'empresa' => 'required|string',
            'status' => 'required|in:disponivel,em_uso,manutencao,descartado'
        ]);

        Equipamento::create($request->all());
        return back()->with('sucesso', 'Equipamento registrado com sucesso!');
    }

    public function update(Request $request, Equipamento $equipamento)
    {
        $request->validate([
            'status' => 'required',
            'empresa' => 'required'
        ]);

        $equipamento->update($request->all());
        return back()->with('sucesso', 'Inventário atualizado!');
    }

    public function destroy(Equipamento $equipamento)
    {
        $equipamento->delete();
        return back()->with('sucesso', 'Item removido do inventário.');
    }
}