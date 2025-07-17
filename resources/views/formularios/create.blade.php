<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __(\'Criar Novo Formulário\') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session(\'success\'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session(\'success\') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route(\'formularios.store\') }}">
                        @csrf

                        <!-- Título -->
                        <div>
                            <x-input-label for="titulo" :value="__(\'Título\')" />
                            <x-text-input id="titulo" class="block mt-1 w-full" type="text" name="titulo" :value="old(\'titulo\')" required autofocus />
                            <x-input-error :messages="$errors->get(\'titulo\')" class="mt-2" />
                        </div>

                        <!-- Descrição -->
                        <div class="mt-4">
                            <x-input-label for="descricao" :value="__(\'Descrição\')" />
                            <textarea id="descricao" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="descricao" rows="5" required>{{ old(\'descricao\') }}</textarea>
                            <x-input-error :messages="$errors->get(\'descricao\')" class="mt-2" />
                        </div>

                        <!-- Categoria -->
                        <div class="mt-4">
                            <x-input-label for="categoria" :value="__(\'Categoria\')" />
                            <select id="categoria" name="categoria" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="geral" {{ old(\'categoria\') == \'geral\' ? \'selected\' : \'\' }}>Geral</option>
                                <option value="suporte" {{ old(\'categoria\') == \'suporte\' ? \'selected\' : \'\' }}>Suporte</option>
                                <option value="sugestao" {{ old(\'categoria\') == \'sugestao\' ? \'selected\' : \'\' }}>Sugestão</option>
                                <option value="reclamacao" {{ old(\'categoria\') == \'reclamacao\' ? \'selected\' : \'\' }}>Reclamação</option>
                            </select>
                            <x-input-error :messages="$errors->get(\'categoria\')" class="mt-2" />
                        </div>

                        <!-- Prioridade -->
                        <div class="mt-4">
                            <x-input-label for="prioridade" :value="__(\'Prioridade\')" />
                            <select id="prioridade" name="prioridade" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                <option value="">Selecione a prioridade</option>
                                <option value="baixa" {{ old(\'prioridade\') == \'baixa\' ? \'selected\' : \'\' }}>Baixa</option>
                                <option value="media" {{ old(\'prioridade\') == \'media\' ? \'selected\' : \'\' }}>Média</option>
                                <option value="alta" {{ old(\'prioridade\') == \'alta\' ? \'selected\' : \'\' }}>Alta</option>
                            </select>
                            <x-input-error :messages="$errors->get(\'prioridade\')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __(\'Enviar Formulário\') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


