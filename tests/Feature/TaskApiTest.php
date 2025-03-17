<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class TaskApiTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_lists_all_tasks()
    {
        // Cria algumas tarefas no banco de dados
        Task::factory()->count(3)->create();

        $response = $this->json('GET', '/api/v1/tasks', [], ['Accept' => 'application/json']);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'item' => [
                         'tasks',
                         'responsibles'
                     ]
                 ]);
    }

    #[Test]
    public function it_creates_a_new_task()
    {
        $data = [
            'title'       => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
            'responsible' => 'João Silva',
            'priority'    => 'alta',
            'deadline'    => now()->addDays(7)->toDateString(),
        ];

        $response = $this->json('POST', '/api/v1/tasks', $data, ['Accept' => 'application/json']);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Nova Tarefa']);

        $this->assertDatabaseHas('tasks', ['title' => 'Nova Tarefa']);
    }

    #[Test]
    public function it_shows_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->json('GET', "/api/v1/tasks/{$task->id}", [], ['Accept' => 'application/json']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $task->id]);
    }

    #[Test]
    public function it_updates_a_task()
    {
        $task = Task::factory()->create([
            'title' => 'Título antigo',
        ]);

        $data = [
            'title'       => 'Título Atualizado',
            'description' => $task->description,
            'responsible' => $task->responsible,
            'priority'    => $task->priority,
            'deadline'    => $task->deadline,
        ];

        $response = $this->json('PUT', "/api/v1/tasks/{$task->id}", $data, ['Accept' => 'application/json']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Título Atualizado']);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Título Atualizado']);
    }

    #[Test]
    public function it_deletes_a_task()
    {
        $task = Task::factory()->create();

        $response = $this->json('DELETE', "/api/v1/tasks/{$task->id}", [], ['Accept' => 'application/json']);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    #[Test]
    public function it_completes_a_task()
    {
        $task = Task::factory()->create(['status' => 'Em andamento']);

        $response = $this->json('POST', "/api/v1/tasks/{$task->id}/complete", [], ['Accept' => 'application/json']);

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'Concluída']);

        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'status' => 'Concluída']);
    }

    #[Test]
    public function it_generates_chatgpt_suggestion_for_a_task()
    {

        $task = Task::factory()->create([
            'title'       => 'Tarefa para ChatGPT',
            'description' => 'Descrição da tarefa para teste',
        ]);

        \Illuminate\Support\Facades\Http::fake([
            'api.openai.com/*' => \Illuminate\Support\Facades\Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => "Passo a passo para realizar a tarefa..."
                        ]
                    ]
                ]
            ], 200)
        ]);

        $response = $this->json('POST', "/api/v1/tasks/{$task->id}/chatgpt", [], ['Accept' => 'application/json']);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'item' => ['response']
                 ]);
    }
}
