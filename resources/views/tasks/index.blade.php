@extends('layouts.app')

@section('content')
{{-- <div class="container">
    <h1>Lista de Tarefas</h1>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Formulário de Filtro -->
    <form method="GET" action="{{ route('tasks.index') }}" class="form-inline mb-3">
        <div class="form-group mr-2">
            <label for="task_number">Número da Tarefa:</label>
            <input type="text" name="task_number" id="task_number" value="{{ request('task_number') }}" class="form-control ml-2">
        </div>
        <div class="form-group mr-2">
            <label for="search">Título/Descrição:</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control ml-2">
        </div>
        <div class="form-group mr-2">
            <label for="responsible">Responsável:</label>
            <select name="responsible" id="responsible" class="form-control ml-2">
                <option value="">Todos</option>
                @foreach($responsibles as $resp)
                    <option value="{{ $resp }}" {{ (request('responsible') == $resp) ? 'selected' : '' }}>{{ $resp }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mr-2">
            <label for="status">Status:</label>
            <select name="status" id="status" class="form-control ml-2">
                <option value="">Todos</option>
                <option value="Em andamento" {{ request('status') == 'Em andamento' ? 'selected' : '' }}>Em andamento</option>
                <option value="Concluída" {{ request('status') == 'Concluída' ? 'selected' : '' }}>Concluída</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Filtrar</button>
    </form>

    <a href="{{ route('tasks.create') }}" class="btn btn-success mb-3">Criar Tarefa</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nº</th>
                <th>Título</th>
                <th>Responsável</th>
                <th>Prioridade</th>
                <th>Deadline</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <td>{{ $task->id }}</td>
                <td>{{ $task->title }}</td>
                <td>{{ $task->responsible }}</td>
                <td>{{ ucfirst($task->priority) }}</td>
                <td>{{ $task->deadline }}</td>
                <td>{{ $task->status }}</td>
                <td>
                    <a href="{{ route('tasks.edit', $task) }}" class="btn btn-warning btn-sm">Editar</a>
                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                    </form>
                    @if($task->status !== 'Concluída')
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-info btn-sm">Concluir</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}


    <!-- Filtros em cards -->
    <div class="bg-white rounded-xl shadow-md p-6 mb-6">
        <h2 class="text-xl font-bold mb-4 text-gray-700 flex items-center">
            <i class="fas fa-filter text-secondary mr-2"></i>
            Filtrar Tarefas
        </h2>

        <form method="GET" action="{{ route('tasks.index') }}" class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="form-group">
                <label for="task_number" class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-hashtag text-gray-400"></i>
                    </div>
                    <input type="text" name="task_number" id="task_number" value="{{ request('task_number') }}"
                        class="pl-10 block w-full rounded-full border-gray-300 bg-gray-50 py-3 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
                </div>
            </div>

            <div class="form-group">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Título/Descrição</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="pl-10 block w-full rounded-full border-gray-300 bg-gray-50 py-3 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
                </div>
            </div>

            <div class="form-group">
                <label for="responsible" class="block text-sm font-medium text-gray-700 mb-1">Responsável</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-user text-gray-400"></i>
                    </div>
                    <select name="responsible" id="responsible"
                        class="pl-10 block w-full rounded-full border-gray-300 bg-gray-50 py-3 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
                        <option value="">Todos</option>
                        @foreach($responsibles as $resp)
                            <option value="{{ $resp }}" {{ (request('responsible') == $resp) ? 'selected' : '' }}>{{ $resp }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-list-check text-gray-400"></i>
                    </div>
                    <select name="status" id="status"
                        class="pl-10 block w-full rounded-full border-gray-300 bg-gray-50 py-3 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
                        <option value="">Todos</option>
                        <option value="Em andamento" {{ request('status') == 'Em andamento' ? 'selected' : '' }}>Em andamento</option>
                        <option value="Concluída" {{ request('status') == 'Concluída' ? 'selected' : '' }}>Concluída</option>
                    </select>
                </div>
            </div>

            <div class="md:col-span-2 lg:col-span-4 flex justify-center">
                <button type="submit" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-3 px-8 rounded-full shadow-md hover:shadow-lg transition duration-300 flex items-center">
                    <i class="fas fa-filter mr-2"></i>
                    Filtrar
                </button>
            </div>
        </form>
    </div>


    <div class="flex flex-col md:flex-row justify-between items-center mb-6">
        <div class="w-full md:w-auto mb-4 md:mb-0">
            <a href="{{ route('tasks.create') }}" class="block w-full md:w-auto bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition duration-300 flex items-center justify-center">
                <i class="fas fa-plus-circle mr-2"></i>
                Nova Tarefa
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 xl:hidden">
        @foreach($tasks as $task)
        <div class="bg-white rounded-xl shadow-md overflow-hidden border-l-4 {{ $task->priority == 'alta' ? 'border-accent' : ($task->priority == 'média' ? 'border-yellow-500' : 'border-green-500') }} hover:shadow-lg transition duration-300">
            <div class="p-5">
                <div class="flex justify-between items-start mb-3 flex-wrap">
                    <div class="flex items-center">
                        <span class="bg-gray-200 text-gray-700 rounded-full h-8 w-8 flex items-center justify-center font-bold text-sm">#{{ $task->id }}</span>
                        <h3 class="ml-3 text-xl font-bold text-gray-800 truncate">{{ $task->title }}</h3>
                    </div>
                    <span class="text-xs font-medium px-2.5 py-0.5 rounded-full {{ $task->status == 'Concluída' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $task->status }}
                    </span>
                </div>

                <div class="mb-4">
                    <div class="flex items-center mb-2 text-gray-600">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span>{{ $task->responsible }}</span>
                    </div>
                    <div class="flex items-center mb-2 text-gray-600">
                        <i class="fas fa-calendar-day mr-2"></i>
                        <span>{{ $task->deadline }}</span>
                    </div>
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-flag mr-2 {{ $task->priority == 'alta' ? 'text-accent' : ($task->priority == 'média' ? 'text-yellow-500' : 'text-green-500') }}"></i>
                        <span>{{ ucfirst($task->priority) }}</span>
                    </div>
                </div>

                <div class="flex flex-wrap space-x-2">
                    <a href="{{ route('tasks.edit', $task) }}" class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-3 py-1 rounded-full text-sm flex items-center">
                        <i class="fas fa-edit mr-1"></i>
                        Editar
                    </a>

                    @if($task->status !== 'Concluída')
                    <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-secondary/10 hover:bg-secondary/20 text-secondary px-3 py-1 rounded-full text-sm flex items-center">
                            <i class="fas fa-check-circle mr-1"></i>
                            Concluir
                        </button>
                    </form>
                    @endif

                    <button type="button"
                            class="px-3 py-1 rounded-full bg-green-100 text-green-600 hover:text-blue-900 open-chatgpt-modal"
                            data-task-id="{{ $task->id }}"
                            data-task-title="{{ $task->title }}"
                            data-task-description="{{ $task->description }}">
                        <i class="fas fa-robot"></i>
                        Como fazer
                    </button>

                    <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-700 px-3 py-1 rounded-full text-sm flex items-center">
                            <i class="fas fa-trash-alt mr-1"></i>
                            Remover
                        </button>
                    </form>
                </div>
            </div>

            <div class="w-full bg-gray-200 h-2">
                <div class="w-full h-2 {{ $task->status == 'Concluída' ? 'bg-green-500' : 'bg-yellow-500' }}" style="width: {{ $task->status == 'Concluída' ? '100%' : '60%' }}"></div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="hidden xl:block mt-8">
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsável</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioridade</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deadline</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($tasks as $task)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="bg-gray-200 text-gray-700 rounded-full h-8 w-8 flex items-center justify-center font-bold text-sm">#{{ $task->id }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $task->title }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-700">{{ $task->responsible }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $task->priority == 'alta' ? 'bg-red-100 text-red-800' :
                                ($task->priority == 'média' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $task->deadline }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                {{ $task->status == 'Concluída' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ $task->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="flex space-x-2">
                                <a href="{{ route('tasks.edit', $task) }}" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>

                                @if($task->status !== 'Concluída')
                                <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-secondary hover:text-secondary-dark">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                                @endif

                                <button type="button"
                                        class="text-blue-600 hover:text-blue-900 open-chatgpt-modal"
                                        data-task-id="{{ $task->id }}"
                                        data-task-title="{{ $task->title }}"
                                        data-task-description="{{ $task->description }}">
                                    <i class="fas fa-robot"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
            <div class="bg-secondary/10 rounded-full p-4 mr-4">
                <i class="fas fa-tasks text-secondary text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total de Tarefas</p>
                <p class="text-3xl font-bold text-gray-800">{{ count($tasks) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
            <div class="bg-green-100 rounded-full p-4 mr-4">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Tarefas Concluídas</p>
                <p class="text-3xl font-bold text-gray-800">{{ $tasks->where('status', 'Concluída')->count() }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md p-6 flex items-center">
            <div class="bg-yellow-100 rounded-full p-4 mr-4">
                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Em Andamento</p>
                <p class="text-3xl font-bold text-gray-800">{{ $tasks->where('status', 'Em andamento')->count() }}</p>
            </div>
        </div>
    </div>
@endsection
