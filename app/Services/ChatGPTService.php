<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatGPTService
{
    private ?string $apiKey;
    private string $apiUrl;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
        $this->apiUrl = 'https://api.openai.com/v1/chat/completions';
        $this->model  = config('services.openai.model', 'gpt-3.5-turbo-16k');
    }

    public function getSuggestionFromTask(Task $task): string
    {
        if (empty($this->apiKey) || $this->apiKey === 'coloque_sua_chave_aqui') {
            Log::warning('Chave da API OpenAI ausente ou inválida.');
            return '<p class="text-red-600">⚠️ Chave da API OpenAI ausente ou inválida. Adicione uma chave válida no arquivo <code>.env</code> como <code>OPENAI_API_KEY</code> e limpe o cache com <code>php artisan config:clear</code>.</p>';
        }

        $prompt = "Qual o melhor jeito e o passo a passo prático para realizar a seguinte tarefa?\n\nTítulo: {$task->title}\nDescrição: {$task->description}";

        $messages = [
            ['role' => 'system', 'content' => 'Você é um executador de tarefas.'],
            ['role' => 'user', 'content' => $prompt]
        ];

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type'  => 'application/json'
            ])->post($this->apiUrl, [
                'model'       => $this->model,
                'messages'    => $messages,
                'max_tokens'  => 1500,
                'temperature' => 0.5,
            ]);

            $result = $response->json();
            Log::debug('OpenAI API response:', $result);

            if (!$response->successful()) {
                Log::error('Erro na API OpenAI: ' . $response->body());
                return '<p class="text-red-600">Erro ao obter resposta do ChatGPT. Verifique a chave da API ou tente novamente mais tarde.</p>';
            }

            if (isset($result['choices'][0]['message']['content'])) {
                return nl2br(e($result['choices'][0]['message']['content']));
            }

            return '<p class="text-red-600">Erro ao interpretar a resposta do ChatGPT.</p>';
        } catch (\Exception $e) {
            Log::error('Exceção ao chamar a API OpenAI', ['erro' => $e->getMessage()]);
            return '<p class="text-red-600">Erro interno ao conectar com o ChatGPT. Consulte os logs da aplicação.</p>';
        }
    }
}
