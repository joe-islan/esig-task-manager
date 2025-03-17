@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Cabeçalho -->
    <div class="mb-8 bg-white rounded-xl shadow-lg p-6">
        <h1 class="text-3xl font-bold text-gray-800">Editar Tarefa</h1>
    </div>

    <!-- Formulário de Edição -->
    <div class="bg-white rounded-xl shadow-md p-6">
        <form method="POST" action="{{ route('tasks.update', $task->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="title" id="title" value="{{ $task->title }}" required class="block w-full rounded-full border-gray-300 bg-gray-50 py-3 px-5 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                <textarea name="description" id="description" class="block w-full rounded-full border-gray-300 bg-gray-50 py-3 px-5 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">{{ $task->description }}</textarea>
            </div>

            <div class="mb-4">
                <label for="responsible" class="block text-sm font-medium text-gray-700 mb-1">Responsável</label>
                <input type="text" name="responsible" id="responsible" value="{{ $task->responsible }}" required class="block w-full rounded-full border-gray-300 bg-gray-50 py-3 px-5 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
            </div>

            <div class="mb-4">
                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Prioridade</label>
                <select name="priority" id="priority" required class="block w-full rounded-full border-gray-300 bg-gray-50 py-3 px-5 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
                    <option value="alta" {{ $task->priority == 'alta' ? 'selected' : '' }}>Alta</option>
                    <option value="média" {{ $task->priority == 'média' ? 'selected' : '' }}>Média</option>
                    <option value="baixa" {{ $task->priority == 'baixa' ? 'selected' : '' }}>Baixa</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">Deadline</label>
                <input type="date" name="deadline" id="deadline" value="{{ $task->deadline }}" class="block w-full rounded-full border-gray-300 bg-gray-50 py-3 px-5 shadow-sm focus:border-secondary focus:ring focus:ring-secondary focus:ring-opacity-30">
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition duration-300">
                    Atualizar
                </button>
                <a href="{{ route('tasks.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-bold py-3 px-6 rounded-full shadow-md hover:shadow-lg transition duration-300">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
