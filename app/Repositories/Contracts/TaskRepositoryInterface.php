<?php

namespace App\Repositories\Contracts;

use App\Models\Task;
use Illuminate\Support\Collection;

interface TaskRepositoryInterface
{
    /**
     * Retorna todas as tarefas filtradas.
     *
     * @param array $filters
     * @return Collection
     */
    public function all(array $filters = []): Collection;

    /**
     * Retorna a lista de responsáveis (de forma normalizada).
     *
     * @return Collection
     */
    public function getResponsibles(): Collection;

    /**
     * Cria uma nova tarefa.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;


    /**
     * Busca uma tarefa pelo seu ID.
     *
     * @param int $id
     * @return Task
     */
    public function find(int $id): Task;

    /**
     * Atualiza uma tarefa existente.
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function update(Task $task, array $data): Task;

    /**
     * Remove uma tarefa.
     *
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool;

    /**
     * Marca uma tarefa como concluída.
     *
     * @param Task $task
     * @return Task
     */
    public function complete(Task $task): Task;
}
