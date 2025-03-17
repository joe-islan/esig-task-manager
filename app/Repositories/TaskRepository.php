<?php

namespace App\Repositories;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Retorna todas as tarefas filtradas com base nos parâmetros.
     *
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection
    {
        $query = Task::query();

        if (!empty($filters['task_number'])) {
            $query->where('id', $filters['task_number']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['responsible'])) {
            $query->whereRaw('LOWER(responsible) = ?', [strtolower($filters['responsible'])]);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('deadline')->get();
    }

    /**
     * Retorna os responsáveis de forma normalizada.
     *
     * @return Collection
     */
    public function getResponsibles(): Collection
    {
        return Task::select(DB::raw('LOWER(responsible) as responsible'))
            ->distinct()
            ->get()
            ->pluck('responsible')
            ->map(function ($name) {
                return ucwords($name);
            });
    }

    /**
     * Cria uma nova tarefa.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task
    {
        return Task::create($data);
    }


    /**
     * Busca uma tarefa pelo seu ID.
     *
     * @param int $id
     * @return Task
     * @throws ModelNotFoundException
     */
    public function find(int $id): Task
    {
        return Task::findOrFail($id);
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
        $task->update($data);
        return $task;
    }

    /**
     * Remove uma tarefa.
     *
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        return $task->delete();
    }

    /**
     * Marca uma tarefa como concluída.
     *
     * @param Task $task
     * @return Task
     */
    public function complete(Task $task): Task
    {
        $task->update(['status' => 'Concluída']);
        return $task;
    }
}
