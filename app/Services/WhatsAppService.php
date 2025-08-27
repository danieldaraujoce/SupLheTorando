<?php

namespace App\Services;

use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $client;
    protected $from;

    public function __construct()
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $this->from = env('TWILIO_WHATSAPP_FROM');
        
        // Validação para garantir que as credenciais estão no .env
        if (!$sid || !$token || !$this->from) {
            throw new Exception('As credenciais da Twilio não estão configuradas no arquivo .env');
        }
        
        $this->client = new Client($sid, $token);
    }

    /**
     * Envia uma mensagem de texto simples.
     * Ideal para responder a um usuário ou enviar uma notificação dentro da janela de 24 horas.
     *
     * @param string $numero O número do destinatário no formato +5588912345678
     * @param string $mensagem A mensagem de texto a ser enviada.
     * @return array
     */
    public function enviarMensagemSimples($numero, $mensagem)
    {
        try {
            $para = 'whatsapp:' . $numero;
            $de = 'whatsapp:' . $this->from;

            $this->client->messages->create(
                $para,
                [
                    'from' => $de,
                    'body' => $mensagem,
                ]
            );

            return ['success' => true];
        } catch (Exception $e) {
            Log::error('Erro Twilio (Simples): ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Envia uma mensagem baseada em um template pré-aprovado na Twilio.
     * Perfeito para iniciar conversas ou enviar notificações padronizadas.
     *
     * @param string $numero O número do destinatário no formato +5588912345678
     * @param string $contentSid O ID do seu template (ex: 'HXb5b6...')
     * @param array $variaveis Um array associativo com as variáveis do template. Ex: ['1' => 'Daniel', '2' => '15:30']
     * @return array
     */
    public function enviarTemplate($numero, $contentSid, $variaveis = [])
    {
        try {
            $para = 'whatsapp:' . $numero;
            $de = 'whatsapp:' . $this->from;

            $this->client->messages->create(
                $para,
                [
                    'from' => $de,
                    'contentSid' => $contentSid,
                    // A Twilio espera as variáveis como uma string JSON
                    'contentVariables' => json_encode($variaveis),
                ]
            );

            return ['success' => true];
        } catch (Exception $e) {
            Log::error('Erro Twilio (Template): ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}