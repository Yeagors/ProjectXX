<!DOCTYPE html>
<html lang="ru">
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- В head добавьте -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Обработанные заявки</title>
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
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
        }
        .modal-container {
            background-color: white;
            margin: 2rem auto;
            padding: 2rem;
            border-radius: 0.5rem;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
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
            <h1 class="text-2xl font-bold text-gray-800">Обработанные заявки</h1>
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
                            <div class="font-medium text-gray-900">{{ ucfirst($request->brand) ?? 'Не указано' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ ucfirst($request->model) ?? 'Не указано' }}</div>
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
                                    <option value="completed" {{ $request->status === 'completed' ? 'selected' : '' }}>Завершена</option>
                                    <option value="create">Создать аукцион</option>
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
                            Нет обработанных заявок
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

<!-- Модальное окно для создания аукциона -->
<div id="auctionModal" class="modal-overlay">
    <div class="modal-container">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Создание аукциона</h2>
            <button onclick="closeModal()" class="text-gray-500 hover:text-gray-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Фотографии автомобиля</h3>
            <div id="auctionGallery" class="flex flex-wrap gap-2"></div>
        </div>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <p class="text-gray-600">Марка:</p>
                <p class="font-medium" id="modalBrand"></p>
            </div>
            <div>
                <p class="text-gray-600">Модель:</p>
                <p class="font-medium" id="modalModel"></p>
            </div>
            <div>
                <p class="text-gray-600">Год выпуска:</p>
                <p class="font-medium" id="modalYear"></p>
            </div>
            <div>
                <p class="text-gray-600">КПП:</p>
                <p class="font-medium" id="modalKpp"></p>
            </div>
            <div>
                <p class="text-gray-600">Пробег:</p>
                <p class="font-medium" id="modalMileage"></p>
            </div>
            <div>
                <p class="text-gray-600">Госномер:</p>
                <p class="font-medium" id="modalLicensePlate"></p>
            </div>
        </div>

        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Отчет о состоянии</h3>
            <div id="inspectionReport" class="space-y-2"></div>
        </div>

        <!-- Добавленные поля для аукциона -->
        <div class="mb-6">
            <h3 class="text-lg font-semibold mb-2">Параметры аукциона</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="startPrice" class="block text-sm font-medium text-gray-700">Начальная цена (руб)</label>
                    <input type="number" id="startPrice" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="mileage" class="block text-sm font-medium text-gray-700">Пробег (км)</label>
                    <input type="number" id="mileage" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="bidStep" class="block text-sm font-medium text-gray-700">Шаг ставки (руб)</label>
                    <input type="number" id="bidStep" value="1000" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="serviceFee" class="block text-sm font-medium text-gray-700">Комиссия сервиса (%)</label>
                    <input type="number" id="serviceFee" value="5" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-3">
            <button onclick="createAuction()" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                Создать аукцион
            </button>
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
        "hideMethod": "fadeOut",
        "tapToDismiss": false
    };
    // Инициализация переменных
    let currentRequestId = null;
    let currentRequestData = null;

    function updateStatus(selectElement) {
        const requestId = selectElement.getAttribute('data-request-id');
        const newStatus = selectElement.value;

        if (newStatus === 'create') {
            // Получаем данные о заявке
            fetch(`/requests/${requestId}/get-data`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
                .then(response => response.json())
                .then(data => {
                    currentRequestId = requestId;
                    currentRequestData = data;
                    showAuctionModal(data.data);
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    selectElement.value = 'completed';
                });
        } else {
            // Для других статусов обновляем сразу
            fetch(`/requests/${requestId}/create`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ status: newStatus , id: requestId})
            })
                .then(response => {
                    if (!response.ok) throw new Error('Ошибка обновления статуса');
                    return response.json();
                })
                .then(data => {
                    toastr.success('Статус обновлен', 'Успешно!');
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    selectElement.value = 'completed';
                });
        }
    }

    function showAuctionModal(data) {
        // Заполняем данные в модальном окне
        document.getElementById('modalBrand').textContent = data.brand ? ucFirst(data.brand) : 'Не указано';
        document.getElementById('modalModel').textContent = data.model ? ucFirst(data.model) : 'Не указано';
        document.getElementById('modalYear').textContent = data.year || 'Не указан';
        document.getElementById('modalKpp').textContent = data.kpp === 'akpp' ? 'Автомат' : data.kpp === 'mkpp' ? 'Механика' : 'Не указано';
        document.getElementById('modalLicensePlate').textContent = data.license_plate || 'Не указан';
        document.getElementById('modalMileage').textContent = data.mileage ? data.mileage + ' км' : 'Не указан';

        // Устанавливаем значение пробега по умолчанию
        document.getElementById('mileage').value = data.mileage || '';

        // Отображаем фотографии
        const galleryContainer = document.getElementById('auctionGallery');
        galleryContainer.innerHTML = '';

        if (data.photos && data.photos.length > 0) {
            data.photos.forEach(photo => {
                const imgWrapper = document.createElement('div');
                imgWrapper.className = 'w-24 h-24 overflow-hidden rounded border border-gray-200';
                imgWrapper.innerHTML = `
                    <a href="${photo.path}" class="gallery-item">
                        <img src="${photo.path}" alt="Фото авто" class="w-full h-full object-cover">
                    </a>
                `;
                galleryContainer.appendChild(imgWrapper);
            });
        }

        // Парсим и отображаем данные осмотра
        const reportContainer = document.getElementById('inspectionReport');
        reportContainer.innerHTML = '';

        try {
            const inspectionData = JSON.parse(data.data);
            for (const [key, value] of Object.entries(inspectionData)) {
                if (key !== 'comments') {
                    const item = document.createElement('div');
                    item.className = 'flex justify-between';
                    item.innerHTML = `
                        <span class="text-gray-700">${translateInspectionKey(key)}:</span>
                        <span class="font-medium ${value ? 'text-green-600' : 'text-red-600'}">
                            ${value ? 'Исправно' : 'Неисправно'}
                        </span>
                    `;
                    reportContainer.appendChild(item);
                }
            }

            if (inspectionData.comments) {
                const commentsItem = document.createElement('div');
                commentsItem.className = 'mt-4';
                commentsItem.innerHTML = `
                    <p class="text-gray-700 font-semibold">Комментарий:</p>
                    <p class="text-gray-800 mt-1">${inspectionData.comments}</p>
                `;
                reportContainer.appendChild(commentsItem);
            }
        } catch (e) {
            reportContainer.innerHTML = '<p class="text-gray-500">Нет данных осмотра</p>';
        }

        // Инициализируем галерею в модальном окне
        lightGallery(document.getElementById('auctionGallery'), {
            selector: '.gallery-item',
            download: false,
            counter: false
        });

        // Показываем модальное окно
        document.getElementById('auctionModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('auctionModal').style.display = 'none';
        // Сбрасываем выпадающий список
        document.querySelector(`select[data-request-id="${currentRequestId}"]`).value = 'completed';
    }

    function createAuction() {
        if (!currentRequestId || !currentRequestData) return;

        // Собираем данные из формы
        const auctionData = {
            ...currentRequestData.data,
            startPrice: $('#startPrice').val(),
            mileage: $('#mileage').val(),
            bidStep: $('#bidStep').val(),
            serviceFee: $('#serviceFee').val(),
            requestId: currentRequestId
        };

        $.ajax({
            url: `/requests/${currentRequestId}/create-auction`,
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: JSON.stringify(auctionData),
            success: function(data) {
                if (!data.success) {
                    throw new Error(data.message || 'Ошибка создания аукциона');
                }
                window.location.href = "{{ route('requests.complete') }}";

                closeModal();
                // Можно обновить страницу или обновить данные
                // location.reload();
            },
            error: function(xhr, status, error) {
                let errorMessage = 'Произошла ошибка при создании аукциона';

                // Пытаемся получить сообщение об ошибке из ответа
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.statusText) {
                    errorMessage = xhr.statusText;
                }

                console.error('Ошибка:', errorMessage);
                toastr.error(errorMessage, 'Ошибка!');
            }
        });
    }

    // Вспомогательные функции
    function ucFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }

    function translateInspectionKey(key) {
        const translations = {
            'body_condition': 'Состояние кузова',
            'engine_condition': 'Состояние двигателя',
            'transmission_condition': 'Состояние трансмиссии',
            'suspension_condition': 'Состояние подвески',
            'interior_condition': 'Состояние салона',
            'electrical_condition': 'Электрооборудование',
            'tires_condition': 'Состояние шин',
            'brakes_condition': 'Тормозная система'
        };
        return translations[key] || key;
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
