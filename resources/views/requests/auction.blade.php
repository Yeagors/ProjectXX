<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки на осмотр</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Добавляем lightgallery для галереи -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery.min.css">
    <style>
        .thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            margin-right: 5px;
            cursor: pointer;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }
        .thumbnail:hover {
            opacity: 0.8;
        }
        /* Стили для Toastr уведомлений */
        .toast {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
        }

        .toast-success {
            background-color: #4CAF50;
        }

        .toast-error {
            background-color: #F44336;
        }

        .toast-warning {
            background-color: #FF9800;
        }

        .toast-info {
            background-color: #2196F3;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h1 class="text-2xl font-bold text-gray-800">Заявки на осмотр</h1>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Фото</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Марка</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Модель</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Госномер</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Оценка</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Телефон</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Год</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Дата</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ФИО</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($request->photos->count() > 0)
                                <div class="flex items-center" id="gallery-{{ $request->id }}">
                                    @foreach($request->photos->take(3) as $photo)
                                        <a href="{{ asset('storage/' . $photo->path) }}"
                                           data-lg-size="1200-800"
                                           class="gallery-item">
                                            <img src="{{ asset('storage/' . $photo->path) }}"
                                                 alt="Фото авто"
                                                 class="thumbnail">
                                        </a>
                                    @endforeach
                                    @if($request->photos->count() > 3)
                                        <span class="text-xs text-gray-500 ml-1">+{{ $request->photos->count() - 3 }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Нет фото</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $request->brand ?? 'Не указано' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $request->model ?? 'Не указано' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $request->license_plate ?? 'Не указан' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $request->amount ?? 'Не указан' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                {{ $request->phone ?? 'Не указан' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->year ?? 'Не указан' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="relative">
                                <select
                                    class="status-select appearance-none block w-full px-3 py-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    data-request-id="{{ $request->id }}"
                                    onchange="updateStatus(this)"
                                >
                                    <option value="new" {{ $request->status === 'new' ? 'selected' : '' }}>Новая</option>
                                    <option value="in_progress" {{ $request->status === 'in_progress' ? 'selected' : '' }}>В работе</option>
                                    <option value="completed" {{ $request->status === 'completed' ? 'selected' : '' }}>Завершена</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                                    </svg>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('d.m.Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ ($request->last_name ?? '') . ' ' . ($request->first_name ?? '') . ' ' . ($request->middle_name ?? '') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                            Нет заявок на осмотр
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $requests->links() }}
        </div>
    </div>
</div>

<!-- Подключаем lightgallery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/lightgallery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    function updateStatus(selectElement) {
        const requestId = selectElement.getAttribute('data-request-id');
        const newStatus = selectElement.value;

        fetch(`/requests/${requestId}/status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ status: newStatus , id: selectElement.getAttribute('data-request-id')})
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка обновления статуса');
                }
                return response.json();
            })
            .then(data => {
                toastr.success('Статус обновлен', 'Успешно!');
            })
            .catch(error => {
                console.error('Ошибка:', error);
                selectElement.value = selectElement.getAttribute('data-previous-value');
            });
    }

    // Инициализация галереи для всех элементов
    document.addEventListener('DOMContentLoaded', function() {
        const galleries = document.querySelectorAll('[id^="gallery-"]');
        galleries.forEach(gallery => {
            lightGallery(gallery, {
                selector: '.gallery-item',
                download: false,
                counter: false
            });
        });
    });
</script>
</body>
</html>
