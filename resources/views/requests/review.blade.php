<!DOCTYPE html>
<html lang="ru">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заявки на этапе осмотра</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Добавляем lightgallery для галереи -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery.min.css">
    <style>
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
        /* Стили для переключателей */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: #4CAF50;
        }
        input:focus + .slider {
            box-shadow: 0 0 1px #4CAF50;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
        }

        .nav-menu ul {
            display: flex;
            gap: 1.5rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-menu a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            padding: 0.5rem 0;
        }

        .nav-menu a:hover {
            color: var(--accent-color);
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
        }
        :root {
            --primary-color: #4a6fa5;
            --secondary-color: #166088;
            --accent-color: #4fc3f7;
            --text-light: #e0e0e0;
            --text-dark: #121212;
            --bg-dark: #121212;
            --bg-darker: #0a0a0a;
            --card-bg: #1e1e1e;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--bg-darker);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body class="bg-gray-100">
<header>
    <div class="logo">Выкуп авто</div>
    @include('components.nav-menu')
</header>
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h1 class="text-2xl font-bold text-gray-800">Заявки на этапе осмотра</h1>
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
                            Нет заявок на этапе осмотра
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

<!-- Модальное окно для оценки компонентов -->
<div id="inspectionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Оценка состояния автомобиля</h3>
            <div class="mt-2 px-7 py-3">
                <form id="inspectionForm">
                    <input type="hidden" name="request_id" id="modalRequestId">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="text-gray-700">Кузов</label>
                            <div class="flex items-center space-x-4">
                                <span>Плохо</span>
                                <label class="switch">
                                    <input type="checkbox" name="body_condition" class="toggle-checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <span>Хорошо</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="text-gray-700">Двигатель</label>
                            <div class="flex items-center space-x-4">
                                <span>Плохо</span>
                                <label class="switch">
                                    <input type="checkbox" name="engine_condition" class="toggle-checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <span>Хорошо</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="text-gray-700">Трансмиссия</label>
                            <div class="flex items-center space-x-4">
                                <span>Плохо</span>
                                <label class="switch">
                                    <input type="checkbox" name="transmission_condition" class="toggle-checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <span>Хорошо</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="text-gray-700">Подвеска</label>
                            <div class="flex items-center space-x-4">
                                <span>Плохо</span>
                                <label class="switch">
                                    <input type="checkbox" name="suspension_condition" class="toggle-checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <span>Хорошо</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <label class="text-gray-700">Салон</label>
                            <div class="flex items-center space-x-4">
                                <span>Плохо</span>
                                <label class="switch">
                                    <input type="checkbox" name="interior_condition" class="toggle-checkbox">
                                    <span class="slider round"></span>
                                </label>
                                <span>Хорошо</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="comments" class="block text-sm font-medium text-gray-700">Комментарии</label>
                        <textarea id="comments" name="comments" rows="3" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm"></textarea>
                    </div>

                    <div class="mt-4 flex justify-end space-x-3">
                        <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                            Отмена
                        </button>
                        <button type="button" onclick="submitInspection()" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            Сохранить
                        </button>
                    </div>
                </form>
            </div>
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
    let currentRequestId = null;
    let previousStatus = null;

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

    function updateStatus(selectElement) {
        const newStatus = selectElement.value;
        currentRequestId = selectElement.getAttribute('data-request-id');
        previousStatus = selectElement.getAttribute('data-previous-value') || 'in_progress';

        if (newStatus === 'completed') {
            // Показываем модальное окно для оценки
            document.getElementById('modalRequestId').value = currentRequestId;
            document.getElementById('inspectionModal').classList.remove('hidden');
        } else {
            // Для других статусов обновляем сразу
            sendStatusUpdate(currentRequestId, newStatus, {});
        }
    }

    function closeModal() {
        document.getElementById('inspectionModal').classList.add('hidden');
        // Возвращаем предыдущий статус
        document.querySelector(`select[data-request-id="${currentRequestId}"]`).value = previousStatus;
    }

    function submitInspection() {
        const formData = new FormData(document.getElementById('inspectionForm'));
        const inspectionData = {
            body_condition: formData.get('body_condition') === 'on',
            engine_condition: formData.get('engine_condition') === 'on',
            transmission_condition: formData.get('transmission_condition') === 'on',
            suspension_condition: formData.get('suspension_condition') === 'on',
            interior_condition: formData.get('interior_condition') === 'on',
            comments: formData.get('comments')
        };

        sendStatusUpdate(currentRequestId, 'completed', inspectionData);
        document.getElementById('inspectionModal').classList.add('hidden');
    }

    function sendStatusUpdate(requestId, status, inspectionData) {
        fetch(`/requests/${requestId}/end`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                status: status,
                inspection_data: inspectionData,
                id: requestId,
            })
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Ошибка обновления статуса');
                }
                return response.json();
            })
            .then(data => {
                toastr.success('Статус обновлен', 'Успешно!');
                location.reload(); // Перезагружаем страницу для обновления данных
            })
            .catch(error => {
                console.error('Ошибка:', error);
                toastr.error('Произошла ошибка при сохранении', 'Ошибка!');
                // Возвращаем предыдущий статус
                document.querySelector(`select[data-request-id="${requestId}"]`).value = previousStatus;
            });
    }
</script>
</body>
</html>
