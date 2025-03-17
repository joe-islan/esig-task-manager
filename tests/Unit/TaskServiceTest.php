<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Task;
use App\Services\TaskService;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Collection;
use Mockery;
use PHPUnit\Framework\Attributes\Test;

class TaskServiceTest extends TestCase
{
    protected $taskRepositoryMock;
    protected TaskService $taskService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepositoryMock = Mockery::mock(TaskRepositoryInterface::class);

        $this->taskService = new TaskService($this->taskRepositoryMock);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    #[Test]
    public function it_lists_all_tasks_and_responsibles()
    {
        $filters = ['search' => 'tarefa'];

        $this->taskRepositoryMock
             ->shouldReceive('all')
             ->once()
             ->with($filters)
             ->andReturn(new Collection([new Task(['title' => 'Teste'])]));

        $this->taskRepositoryMock
             ->shouldReceive('getResponsibles')
             ->once()
             ->andReturn(collect(['João Silva']));

        $result = $this->taskService->listAll($filters);

        $this->assertArrayHasKey('tasks', $result);
        $this->assertArrayHasKey('responsibles', $result);
    }

    #[Test]
    public function it_creates_a_new_task()
    {
        $data = [
            'title'       => 'Nova Tarefa',
            'description' => 'Descrição da tarefa',
            'responsible' => 'João Silva',
            'priority'    => 'alta',
            'deadline'    => now()->toDateString(),
        ];

        $fakeTask = new Task($data);

        $this->taskRepositoryMock
             ->shouldReceive('create')
             ->once()
             ->with($data)
             ->andReturn($fakeTask);

        $result = $this->taskService->create($data);

        $this->assertInstanceOf(Task::class, $result);
        $this->assertEquals('Nova Tarefa', $result->title);
    }
}
