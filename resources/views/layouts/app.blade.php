<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Gerenciador de Tarefas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#58cc02',
                        'primary-dark': '#46a302',
                        'secondary': '#1cb0f6',
                        'secondary-dark': '#0c95d4',
                        'accent': '#ff4b4b',
                        'light-bg': '#f7f7f7',
                    }
                }
            }
        }
    </script>
</head>

<body class="bg-light-bg min-h-screen font-sans">
    <div class="container mx-auto px-4 py-8">
        <!-- Cabeçalho -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="bg-primary rounded-full p-3 mr-4">
                    <i class="fas fa-tasks text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">SuperTarefas</h1>
            </div>

            <!-- Gamificação: Estatísticas -->
            <div class="flex space-x-4">
                <div class="flex items-center bg-secondary/10 px-4 py-2 rounded-full">
                    <i class="fas fa-star text-yellow-500 mr-2"></i>
                    <span class="font-bold">120 pontos</span>
                </div>
                <div class="flex items-center bg-primary/10 px-4 py-2 rounded-full">
                    <i class="fas fa-fire text-accent mr-2"></i>
                    <span class="font-bold">3 dias</span>
                </div>
            </div>
        </div>

        <!-- Mensagem de sucesso -->
        @if (session('success'))
            <div
                class="bg-green-100 border-l-4 border-primary rounded-r-lg p-4 mb-6 flex items-center shadow-md animate-bounce">
                <i class="fas fa-check-circle text-primary text-xl mr-3"></i>
                <span class="text-green-800">{{ session('success') }}</span>
                <button class="ml-auto text-green-800 hover:text-green-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        @yield('content')

        <!-- Rodapé -->
        <footer class="mt-12 text-center text-gray-500 text-sm">
            <p>SuperTarefas &copy; {{ date('Y') }} - O melhor gerenciador de tarefas</p>
            <div class="mt-2 flex justify-center space-x-4">
                <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-github"></i></a>
                <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-linkedin"></i></a>
            </div>
        </footer>
    </div>

    <!-- Modal ChatGPT -->
    <div id="chatgpt-modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-1/2 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Sugestão do ChatGPT</h3>
                <button id="close-chatgpt-modal" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="chatgpt-content" class="prose max-h-96 overflow-y-auto">
                <p class="text-center">Carregando...</p>
            </div>
            <div class="mt-4 flex justify-end space-x-2">
                <button id="copy-chatgpt-response"
                    class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                    Copiar Resposta
                </button>
                <button id="modal-close-btn"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                    Fechar
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('chatgpt-modal');
        const closeModalBtns = [
            document.getElementById('close-chatgpt-modal'),
            document.getElementById('modal-close-btn')
        ];
        const chatgptContent = document.getElementById('chatgpt-content');
        const copyBtn = document.getElementById('copy-chatgpt-response');

        function openChatGPTModal(taskId) {
            modal.classList.remove('hidden');
            chatgptContent.innerHTML = '<p class="text-center">Carregando...</p>';

            fetch("{{ url('/api/v1/tasks') }}/" + taskId + "/chatgpt", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                }
            })
            .then(response => response.json())
            .then(data => {
                chatgptContent.innerHTML = data.item.response;
            })
            .catch(error => {
                chatgptContent.innerHTML = '<p class="text-red-600 text-center">Erro ao carregar a resposta.</p>';
                console.error(error);
            });
        }

        document.querySelectorAll('.open-chatgpt-modal').forEach(button => {
            button.addEventListener('click', function() {
                const taskId = this.getAttribute('data-task-id');
                openChatGPTModal(taskId);
            });
        });

        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                modal.classList.add('hidden');
            });
        });

        copyBtn.addEventListener('click', function() {
            const textToCopy = chatgptContent.innerText;
            navigator.clipboard.writeText(textToCopy)
                .then(() => alert('Resposta copiada para a área de transferência!'))
                .catch(err => console.error('Erro ao copiar: ', err));
        });
    });
    </script>



    <!-- Script para funcionalidades dinâmicas -->
    <script>
        // Fechar mensagem de sucesso
        document.addEventListener('DOMContentLoaded', function() {
            const closeButtons = document.querySelectorAll('.alert button');
            closeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            });

            // Esconde automaticamente a mensagem após 5 segundos
            setTimeout(() => {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => {
                    alert.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                });
            }, 5000);
        });
    </script>
</body>

</html>
