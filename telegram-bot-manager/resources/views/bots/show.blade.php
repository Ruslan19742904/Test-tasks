<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $bot->name }} - Управление
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Форма рассылки -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Сделать рассылку</h3>

                    <form id="broadcast-form" method="POST" action="{{ route('bots.broadcast', $bot) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label for="message" class="block text-sm font-medium text-gray-700">Текст сообщения</label>
                            <textarea name="message" id="message" rows="4"
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">Прикрепить файл (необязательно)</label>
                            <input type="file" name="file" id="file"
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>

                        <div id="broadcast-status" class="mb-4 hidden"></div>

                        <button type="submit" id="broadcast-btn"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Отправить рассылку
                        </button>
                    </form>
                </div>
            </div>

            <!-- Список подписчиков -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">Подписчики ({{ $subscribers->total() }})</h3>

                    @if($subscribers->isEmpty())
                        <p class="text-gray-500">У этого бота пока нет подписчиков</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID чата</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Имя</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($subscribers as $subscriber)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $subscriber->chat_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $subscriber->first_name }} {{ $subscriber->last_name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($subscriber->username)
                                                @{{ $subscriber->username }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <form method="POST" action="{{ route('bots.subscribers.destroy', [$bot, $subscriber]) }}"
                                                  onsubmit="return confirm('Удалить подписчика?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900">
                                                    Удалить
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $subscribers->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.getElementById('broadcast-form').addEventListener('submit', function(e) {
                e.preventDefault();

                const form = this;
                const formData = new FormData(form);
                const submitBtn = document.getElementById('broadcast-btn');
                const statusDiv = document.getElementById('broadcast-status');

                submitBtn.disabled = true;
                submitBtn.textContent = 'Отправка...';
                statusDiv.classList.add('hidden');

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        statusDiv.classList.remove('hidden');
                        statusDiv.className = 'mb-4 p-4 bg-green-100 text-green-700 rounded';
                        statusDiv.textContent = data.message;
                        form.reset();
                    })
                    .catch(error => {
                        statusDiv.classList.remove('hidden');
                        statusDiv.className = 'mb-4 p-4 bg-red-100 text-red-700 rounded';
                        statusDiv.textContent = 'Произошла ошибка при запуске рассылки';
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Отправить рассылку';
                    });
            });
        </script>
    @endpush
</x-app-layout>
