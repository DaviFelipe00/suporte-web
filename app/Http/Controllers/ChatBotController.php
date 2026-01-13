<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Solicitacao;
use Exception;

class ChatBotController extends Controller
{
    public function handle(Request $request)
    {
        try {
            $message = $request->input('message');
            $apiKey = config('services.gemini.key');

            if (!$apiKey) {
                return response()->json(['response' => '⚠️ Configuração: Chave de API não encontrada no servidor.']);
            }

            // Busca inteligente de protocolo no banco
            $protocoloStatus = "";
            if (preg_match('/\d{8}-[A-Z0-9]{6}/', $message, $matches)) {
                $solicitacao = Solicitacao::where('protocolo', $matches[0])->first();
                if ($solicitacao) {
                    $protocoloStatus = " [INFO DO SISTEMA: O protocolo {$matches[0]} pertence a {$solicitacao->nome_solicitante} e o status é: {$solicitacao->status}]";
                }
            }

            // Chamada para a API
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}", [
                'contents' => [
                    [
                        'role' => 'user',
                        'parts' => [['text' => "Você é o suporte da Simplemind. Instruções: Seja educado e breve. Se houver esta informação interna, use-a: {$protocoloStatus}. Mensagem do usuário: {$message}"]]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                return response()->json(['response' => '🤖 Desculpe, estou com dificuldades para conectar à minha inteligência agora.']);
            }

            $data = $response->json();
            $botResponse = data_get($data, 'candidates.0.content.parts.0.text', 'Não consegui processar sua dúvida.');

            return response()->json(['response' => $botResponse]);

        } catch (Exception $e) {
            return response()->json(['response' => '🤖 Ocorreu um erro técnico no processamento do chat.']);
        }
    }
}