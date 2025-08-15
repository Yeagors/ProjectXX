<!-- resources/views/livewire/requests-table.blade.php -->
<div class="bg-white shadow-sm rounded-lg overflow-hidden">
    <div class="p-4 border-b border-gray-200 bg-gray-50">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h3 class="text-lg font-medium text-gray-900 mb-2 md:mb-0">Заявки на аукцион</h3>

            <div class="flex flex-col sm:flex-row gap-2">
                <input wire:model.debounce.300ms="search" type="text"
                       placeholder="Поиск по модели или номеру..."
                       class="px-3 py-2 border rounded-md shadow-sm">

                <select wire:model="status" class="px-3 py-2 border rounded-md shadow-sm">
                    <option value="">Все статусы</option>
                    <option value="new">Новые</option>
                    <option value="in_progress">В работе</option>
                    <option value="completed">Завершённые</option>
                </select>
            </div>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Модель</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Госномер</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($requests as $request)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="font-medium text-gray-900">{{ $request->car_model }}</div>
                        <div class="text-sm text-gray-500">{{ $request->year }} год</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                            {{ $request->license_plate }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $request->created_at->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $request->status === 'new' ? 'bg-blue-100 text-blue-800' :
                               ($request->status === 'in_progress' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                            {{ $request->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button wire:click="editRequest({{ $request->id }})"
                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                            Редактировать
                        </button>
                        <button wire:click="deleteRequest({{ $request->id }})"
                                class="text-red-600 hover:text-red-900">
                            Удалить
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                        Нет заявок, соответствующих критериям поиска
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-3 border-t border-gray-200">
        {{ $requests->links() }}
    </div>
</div>
