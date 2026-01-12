<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Exibe o formulário de cadastro (agora interno para administradores).
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Processa a criação de um novo administrador.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validação rigorosa dos dados recebidos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 2. Criação do novo usuário no banco de dados
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Criptografia de senha obrigatória
        ]);

        // 3. Disparo do evento de registro (pode ser usado para e-mails de boas-vindas)
        event(new Registered($user));

        /** * AJUSTE SÊNIOR: 
         * Removemos o 'Auth::login($user);' para que VOCÊ continue logado 
         * enquanto cadastra novos membros na equipe Simplemind.
         */

        // 4. Redirecionamento para o painel com mensagem de feedback
        return redirect()->route('admin.index')
            ->with('sucesso', 'Novo administrador cadastrado com sucesso!');
    }
}