<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Services\ChatGPTService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PHPUnit\Framework\Attributes\Test;

class ChatGPTServiceTest extends TestCase
{
    #[Test]
    public function it_returns_suggestion_when_api_is_successful()
    {
        config(['services.openai.api_key' => 'test-api-key']);

        $task = new Task([
            'title'       => 'Tarefa Teste',
            'description' => 'Descrição de teste',
        ]);

        Http::fake([
            'api.openai.com/*' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => "Passo a passo para realizar a tarefa com sucesso."
                        ]
                    ]
                ]
            ], 200)
        ]);

        $service = new ChatGPTService();

        $suggestion = $service->getSuggestionFromTask($task);

        $this->assertStringContainsString('Passo a passo', $suggestion);
    }

    #[Test]
    public function it_returns_error_message_when_api_key_is_missing()
    {

        $task = new Task([
            'title'       => 'Tarefa Teste',
            'description' => 'Descrição de teste',
        ]);

        config(['services.openai.api_key' => '']);

        $service = new ChatGPTService();

        $suggestion = $service->getSuggestionFromTask($task);

        $this->assertStringContainsString('Chave da API OpenAI ausente ou inválida', $suggestion);
    }
}
