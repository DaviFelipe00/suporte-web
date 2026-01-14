<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatbotController extends Controller
{
    /**
     * Processa a mensagem do usuário usando lógica local (sem IA/APIs externas)
     */
    public function handle(Request $request)
    {
        try {
            $request->validate([
                'message' => 'required|string|max:1000'
            ]);

            $userMessage = mb_strtolower($request->input('message'));

            // Log para auditoria interna
            Log::info('Chatbot (Local) processando mensagem:', ['message' => $userMessage]);

            // Obtém a resposta baseada na lógica de palavras-chave
            $response = $this->getSystemResponse($userMessage);

            return response()->json([
                'success' => true,
                'response' => $response
            ]);

        } catch (\Exception $e) {
            Log::error('Erro no chatbot local:', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'response' => 'Ocorreu um erro no processamento. Por favor, tente novamente.'
            ], 500);
        }
    }

    /**
     * Motor de respostas pré-programadas baseadas no sistema de suporte
     */
    private function getSystemResponse($message)
    {
        // 1. Abertura de Chamados / Solicitações
        if ($this->containsAny($message, ['abrir', 'chamado', 'ticket', 'solicitação', 'solicitacao', 'nova dúvida'])) {
            return "Para abrir uma nova solicitação, clique em 'Suporte Técnico' no menu superior. Você precisará preencher seu nome, e-mail e a descrição da sua dúvida. Ao finalizar, um número de protocolo será gerado para você.";
        }

        // 2. Acompanhamento e Protocolos
        if ($this->containsAny($message, ['acompanhar', 'protocolo', 'status', 'verificar', 'meu pedido'])) {
            return "Para verificar o andamento do seu chamado, acesse a página 'Acompanhar Chamado' e digite o seu número de protocolo. Lá você poderá ver o status atual (Pendente, Em Andamento ou Resolvido) e a resposta do administrador.";
        }

        // 3. Horários e Atendimento
        if ($this->containsAny($message, ['horário', 'atendimento', 'horas', 'aberto', 'funciona'])) {
            return "Nosso atendimento humano funciona de segunda a sexta, das 08h às 18h. No entanto, o sistema de abertura de chamados fica disponível 24 horas por dia, 7 dias por semana.";
        }

        // 4. Contatos Oficiais
        if ($this->containsAny($message, ['contato', 'telefone', 'zap', 'whatsapp', 'email', 'e-mail'])) {
            return "Você pode falar diretamente com nosso time administrativo por:\n📧 E-mail: suporte@simplemind.com.br\n📞 Telefone/WhatsApp: +55 (81) 99999-9999";
        }

        // 5. Saudações e Ajuda Geral
        if ($this->containsAny($message, ['olá', 'oi', 'bom dia', 'boa tarde', 'boa noite', 'ajuda', 'ajudar'])) {
            return "Olá! 👋 Eu sou o assistente virtual da Simplemind. Posso te ajudar com:\n\n" .
                   "• 'Abrir chamado': Saiba como criar uma solicitação.\n" .
                   "• 'Protocolo': Como acompanhar seu chamado.\n" .
                   "• 'Contato': Nossos canais de suporte humano.";
        }

        // 6. Agradecimentos
        if ($this->containsAny($message, ['obrigado', 'obrigada', 'valeu', 'obg', 'tchau'])) {
            return "Por nada! Estamos à disposição para ajudar. Tenha um excelente dia! 😊";
        }

        // Resposta padrão (Fallback)
        return "Ainda não fui treinado para responder essa dúvida específica. 😕\n\nTente palavras simples como: 'Chamado', 'Status', 'Horário' ou 'Contato'.";
    }

    /**
     * Função auxiliar para verificar múltiplas palavras-chave em uma frase
     */
    private function containsAny($haystack, array $needles)
    {
        foreach ($needles as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }
}