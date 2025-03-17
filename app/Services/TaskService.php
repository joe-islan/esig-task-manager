<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TaskService
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * Retorna a lista de tarefas filtradas e os responsáveis.
     *
     * @param array $filters
     * @return array
     */
    public function listAll(array $filters = []): array
    {
        $tasks = $this->taskRepository->all($filters);
        $responsibles = $this->taskRepository->getResponsibles();

        return [
            'tasks' => $tasks,
            'responsibles' => $responsibles,
        ];
    }

    /**
     * Cria uma nova tarefa.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        return $this->taskRepository->create($data);
    }

    /**
     * Busca uma tarefa pelo seu ID.
     *
     * @param int $id
     * @return Task
     */
    public function find(int $id): Task
    {
        return $this->taskRepository->find($id);
    }

    /**
     * Atualiza uma tarefa existente.
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function update(Task $task, array $data): Task
    {
        return $this->taskRepository->update($task, $data);
    }

    /**
     * Remove uma tarefa.
     *
     * @param Task $task
     * @return void
     */
    public function destroy(Task $task): void
    {
        $this->taskRepository->delete($task);
    }

    /**
     * Marca uma tarefa como concluída.
     *
     * @param Task $task
     * @return Task
     */
    public function complete(Task $task): Task
    {
        return $this->taskRepository->complete($task);
    }
}
