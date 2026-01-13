<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Processa a mensagem do usuário e retorna resposta do bot
     */
    public function handle(Request $request)
    {
        try {
            // Valida a entrada
            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            $userMessage = $request->input('message');

            // Log para debug
            Log::info('Chatbot recebeu mensagem:', ['message' => $userMessage]);

            // Chama a API da OpenAI (ou outra LLM)
            $response = $this->callOpenAI($userMessage);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no chatbot:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'response' => 'Desculpe, ocorreu um erro. Tente novamente em instantes.'
            ], 500);
        }
    }

    /**
     * Chama a API da OpenAI
     */
    private function callOpenAI($message)
    {
        $apiKey = env('OPENAI_API_KEY');

        if (!$apiKey) {
            Log::warning('OPENAI_API_KEY não configurada');
            return $this->getFallbackResponse($message);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Você é o assistente virtual da Simplemind, uma empresa de suporte técnico. Seja amigável, prestativo e profissional. Ajude os usuários com dúvidas sobre abertura de chamados, acompanhamento de tickets e informações gerais sobre nossos serviços.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'max_tokens' => 500,
                'temperature' => 0.7,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? 'Desculpe, não consegui processar sua mensagem.';
            }

            Log::error('Erro na API OpenAI:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Exception $e) {
            Log::error('Exceção ao chamar OpenAI:', ['error' => $e->getMessage()]);
            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Chama a API da Anthropic (Claude)
     */
    private function callClaude($message)
    {
        $apiKey = env('ANTHROPIC_API_KEY');

        if (!$apiKey) {
            Log::warning('ANTHROPIC_API_KEY não configurada');
            return $this->getFallbackResponse($message);
        }

        try {
            $response = Http::withHeaders([
                'x-api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'anthropic-version' => '2023-06-01'
            ])->timeout(30)->post('https://api.anthropic.com/v1/messages', [
                'model' => 'claude-3-haiku-20240307',
                'max_tokens' => 500,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $message
                    ]
                ],
                'system' => 'Você é o assistente virtual da Simplemind, uma empresa de suporte técnico. Seja amigável, prestativo e profissional.'
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return $data['content'][0]['text'] ?? 'Desculpe, não consegui processar sua mensagem.';
            }

            Log::error('Erro na API Claude:', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return $this->getFallbackResponse($message);

        } catch (\Exception $e) {
            Log::error('Exceção ao chamar Claude:', ['error' => $e->getMessage()]);
            return $this->getFallbackResponse($message);
        }
    }

    /**
     * Retorna respostas pré-definidas caso a API falhe
     */
    private function getFallbackResponse($message)
    {
        $message = strtolower($message);

        // Respostas básicas por palavras-chave
        if (str_contains($message, 'chamado') || str_contains($message, 'ticket')) {
            return "Para abrir um chamado, clique em 'Suporte Técnico' no menu acima e preencha o formulário. Você receberá um número de protocolo para acompanhamento.";
        }

        if (str_contains($message, 'acompanhar') || str_contains($message, 'protocolo') || str_contains($message, 'status')) {
            return "Para acompanhar seu chamado, clique em 'Acompanhar Chamado' no menu e informe o número do protocolo que você recebeu.";
        }

        if (str_contains($message, 'horário') || str_contains($message, 'atendimento')) {
            return "Nosso horário de atendimento é de segunda a sexta, das 8h às 18h. Chamados podem ser abertos 24/7 pelo site.";
        }

        if (str_contains($message, 'contato') || str_contains($message, 'telefone') || str_contains($message, 'email')) {
            return "Você pode nos contatar por:\n📧 Email: suporte@simplemind.com.br\n📞 Telefone: +55 (81) 99999-9999\n💬 WhatsApp: Clique no ícone verde no canto inferior esquerdo";
        }

        if (str_contains($message, 'olá') || str_contains($message, 'oi') || str_contains($message, 'bom dia') || str_contains($message, 'boa tarde')) {
            return "Olá! 👋 Como posso ajudar você hoje? Posso auxiliar com abertura de chamados, acompanhamento de tickets ou informações gerais sobre nossos serviços.";
        }

        if (str_contains($message, 'obrigado') || str_contains($message, 'valeu')) {
            return "Por nada! Estou aqui para ajudar. Se precisar de mais alguma coisa, é só chamar! 😊";
        }

        // Resposta padrão
        return "Posso ajudar você com:\n• Abertura de chamados\n• Acompanhamento de tickets\n• Informações sobre nossos serviços\n\nSobre qual desses tópicos você gostaria de saber mais?";
    }
}