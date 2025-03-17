<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use App\Services\ChatGPTService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Psr\Log\LoggerInterface;
use App\Helpers\ControllerHelper;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

/**
 * @OA\Info(
 *     title="API de Tarefas",
 *     version="1.0.0"
 * )
 * @OA\PathItem(path="/api/v1/tasks")
 */

class TaskApiController extends Controller
{
    public function __construct(
        private TaskService $taskService,
        private ChatGPTService $chatGPTService,
        private LoggerInterface $logger,
        private ControllerHelper $helper
    ) {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks",
     *     summary="Lista todas as tarefas",
     *     description="Retorna a lista de tarefas cadastradas no sistema.",
     *     tags={"Tarefas"},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="task_number",
     *         in="query",
     *         description="Filtrar pela número da tarefa",
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Filtrar por título ou descrição",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="responsible",
     *         in="query",
     *         description="Filtrar por responsável",
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por status",
     *         @OA\Schema(type="string", enum={"Em andamento", "Concluída"})
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de tarefas recuperada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lista de tarefas recuperada com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="tasks", type="array", @OA\Items(type="object")),
     *                 @OA\Property(property="responsibles", type="array", @OA\Items(type="string"))
     *             )
     *         )
     *     ),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['task_number', 'search', 'responsible', 'status']);
            $result = $this->taskService->listAll($filters);
            return $this->helper->successJsonResponse('Lista de tarefas recuperada com sucesso', $result);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao listar tarefas', ['erro' => $e->getMessage()]);
            return $this->helper->errorJsonResponse('Erro interno ao listar tarefas', null, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks",
     *     summary="Cria uma nova tarefa",
     *     description="Cria uma nova tarefa no sistema.",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "responsible", "priority"},
     *             @OA\Property(property="title", type="string", example="Nova Tarefa"),
     *             @OA\Property(property="description", type="string", example="Descrição da tarefa"),
     *             @OA\Property(property="responsible", type="string", example="João Silva"),
     *             @OA\Property(property="priority", type="string", example="alta", enum={"alta", "média", "baixa"}),
     *             @OA\Property(property="deadline", type="string", format="date", example="2025-12-31")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Tarefa criada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa criada com sucesso"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        try {
            $task = $this->taskService->create($request->validated());
            return $this->helper->successJsonResponse('Tarefa criada com sucesso', $task, 201);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao criar tarefa', ['erro' => $e->getMessage(), 'dados' => $request->all()]);
            return $this->helper->errorJsonResponse('Erro interno ao criar tarefa', null, 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/tasks/{id}",
     *     summary="Exibe os detalhes de uma tarefa",
     *     description="Retorna os detalhes de uma tarefa específica.",
     *     tags={"Tarefas"},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da tarefa recuperados com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function show(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->find($id);
            return $this->helper->successJsonResponse('Detalhes da tarefa recuperados com sucesso', $task);
        } catch (ModelNotFoundException $e) {
            return $this->helper->errorJsonResponse('Tarefa não encontrada', null, 404);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao exibir tarefa', ['erro' => $e->getMessage()]);
            return $this->helper->errorJsonResponse('Erro interno ao exibir tarefa', null, 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/tasks/{id}",
     *     summary="Atualiza uma tarefa existente",
     *     description="Atualiza os dados de uma tarefa.",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa a ser atualizada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "responsible", "priority"},
     *             @OA\Property(property="title", type="string", example="Tarefa Atualizada"),
     *             @OA\Property(property="description", type="string", example="Descrição atualizada"),
     *             @OA\Property(property="responsible", type="string", example="Maria Oliveira"),
     *             @OA\Property(property="priority", type="string", example="média", enum={"alta", "média", "baixa"}),
     *             @OA\Property(property="deadline", type="string", format="date", example="2025-10-10")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa atualizada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa atualizada com sucesso"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function update(UpdateTaskRequest $request, int $id): JsonResponse
    {
        try {
            $task = $this->taskService->find($id);
            $updatedTask = $this->taskService->update($task, $request->validated());
            return $this->helper->successJsonResponse('Tarefa atualizada com sucesso', $updatedTask);
        } catch (ModelNotFoundException $e) {
            return $this->helper->errorJsonResponse('Tarefa não encontrada', null, 404);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao atualizar tarefa', ['erro' => $e->getMessage(), 'dados' => $request->all()]);
            return $this->helper->errorJsonResponse('Erro interno ao atualizar tarefa', null, 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/tasks/{id}",
     *     summary="Remove uma tarefa",
     *     description="Remove uma tarefa do sistema.",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa a ser removida",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa removida com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa removida com sucesso")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taskService->destroy($this->taskService->find($id));
            return $this->helper->successJsonResponse('Tarefa removida com sucesso', null);
        } catch (ModelNotFoundException $e) {
            return $this->helper->errorJsonResponse('Tarefa não encontrada', null, 404);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao remover tarefa', ['erro' => $e->getMessage()]);
            return $this->helper->errorJsonResponse('Erro interno ao remover tarefa', null, 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/tasks/{id}/complete",
     *     summary="Marca uma tarefa como concluída",
     *     description="Atualiza o status da tarefa para 'Concluída'.",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa a ser marcada como concluída",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Tarefa concluída com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Tarefa concluída com sucesso"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function complete(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->find($id);
            $completedTask = $this->taskService->complete($task);
            return $this->helper->successJsonResponse('Tarefa concluída com sucesso', $completedTask);
        } catch (ModelNotFoundException $e) {
            return $this->helper->errorJsonResponse('Tarefa não encontrada', null, 404);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao concluir tarefa', ['erro' => $e->getMessage()]);
            return $this->helper->errorJsonResponse('Erro interno ao concluir tarefa', null, 500);
        }
    }



    /**
     * @OA\Post(
     *     path="/api/v1/tasks/{id}/chatgpt",
     *     summary="Gera sugestão do ChatGPT para uma tarefa",
     *     description="Utiliza o título e a descrição da tarefa para gerar uma sugestão com o melhor jeito e o passo a passo prático para realizar a tarefa.",
     *     tags={"Tarefas"},
     *     security={{"bearerAuth":{}}},
     *
     *     @OA\Parameter(
     *         name="Accept",
     *         in="header",
     *         description="Define o formato de resposta esperado. Deve ser application/json",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             default="application/json"
     *         )
     *     ),
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID da tarefa para a qual a sugestão do ChatGPT será gerada",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Sugestão gerada com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Sugestão gerada com sucesso"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="response", type="string")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=404, description="Tarefa não encontrada"),
     *     @OA\Response(response=500, description="Erro interno do servidor")
     * )
     */
    public function chatgpt(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->find($id);
            $response = $this->chatGPTService->getSuggestionFromTask($task);
            return $this->helper->successJsonResponse('Sugestão gerada com sucesso', ['response' => $response]);
        } catch (ModelNotFoundException $e) {
            return $this->helper->errorJsonResponse('Tarefa não encontrada', null, 404);
        } catch (\Exception $e) {
            $this->logger->error('Erro ao gerar sugestão do ChatGPT', ['erro' => $e->getMessage()]);
            return $this->helper->errorJsonResponse('Erro interno ao gerar sugestão do ChatGPT', null, 500);
        }
    }

}
