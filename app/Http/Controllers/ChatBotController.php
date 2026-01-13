<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Solicitacao;

class ChatBotController extends Controller
{
    public function handle(Request $request)
{
    $message = $request->input('message');
    $apiKey = config('services.gemini.key');

    if (!$apiKey) {
        return response()->json(['error' => 'API Key do Gemini não configurada'], 500);
    }

    $protocoloStatus = "";

    if (preg_match('/\d{8}-[A-Z0-9]{6}/', $message, $matches)) {
        $solicitacao = Solicitacao::where('protocolo', $matches[0])->first();

        if ($solicitacao) {
            $protocoloStatus = " [INFO DO SISTEMA: O protocolo {$matches[0]} está com status: {$solicitacao->status}]";
        }
    }

    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post(
        "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key={$apiKey}",
        [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        [
                            'text' => "Você é o assistente virtual da Simplemind, uma plataforma de suporte técnico.
                            Seja educado, breve e prestativo.
                            Use informações internas se disponíveis.
                            {$protocoloStatus}
                            Mensagem do usuário: {$message}"
                        ]
                    ]
                ]
            ]
        ]
    );

    if (!$response->successful()) {
        return response()->json([
            'error' => 'Erro ao chamar Gemini',
            'details' => $response->body()
        ], 500);
    }

    $data = $response->json();

    $botResponse = data_get(
        $data,
        'candidates.0.content.parts.0.text',
        'Desculpe, tive um erro ao processar sua dúvida.'
    );

    return response()->json(['response' => $botResponse]);
}

}