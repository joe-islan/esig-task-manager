<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Services\ChatGPTService;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function __construct(
        private TaskService $taskService,
        private ChatGPTService $chatGPTService
    ){
    }

    public function index(Request $request)
    {
        $filters = $request->only(['task_number', 'search', 'responsible', 'status']);
        $result = $this->taskService->listAll($filters);

        return view('tasks.index', [
            'tasks'        => $result['tasks'],
            'responsibles' => $result['responsibles'],
        ]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(StoreTaskRequest $request)
    {
        $this->taskService->create($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Tarefa criada com sucesso!');
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->taskService->update($task, $request->validated());

        return redirect()->route('tasks.index')->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function destroy(Task $task)
    {
        $this->taskService->destroy($task);

        return redirect()->route('tasks.index')->with('success', 'Tarefa removida com sucesso!');
    }

    public function complete(Task $task)
    {
        $this->taskService->complete($task);

        return redirect()->route('tasks.index')->with('success', 'Tarefa concluída com sucesso!');
    }
}
