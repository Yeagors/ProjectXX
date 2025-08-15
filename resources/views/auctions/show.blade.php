<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $auction->brand }} {{ $auction->model }} - Аукцион</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <style>
        .gallery-thumbnail {
            height: 100px;
            width: 150px;
            object-fit: cover;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .gallery-thumbnail:hover {
            border-color: #1976d2;
        }
        .bid-history {
            max-height: 300px;
            overflow-y: auto;
        }
        .comment-box {
            border-left: 3px solid #1976d2;
        }
        .auction-ended {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .lg-actions .lg-next, .lg-actions .lg-prev {
            background-color: rgba(0,0,0,0.5);
            border-radius: 50%;
            padding: 10px;
            color: white;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="container mx-auto py-8 px-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        @php
            $isAuctionEnded = \Carbon\Carbon::parse($auction->end_date)->isPast();
            $nextBidAmount = ($auction->current_bid ?? $auction->start_price) + $auction->bid_step;
        @endphp

        @if($isAuctionEnded)
            <div class="auction-ended text-center">
                <h2 class="text-2xl font-bold text-red-600 mb-2">Аукцион завершен</h2>
                <p class="text-gray-700">Этот аукцион уже завершился. Вы можете просмотреть информацию о лоте, но больше нельзя делать ставки или оставлять комментарии.</p>
            </div>
        @endif

        <!-- Галерея изображений -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">
            <div class="md:col-span-2">
                <div id="main-gallery" class="grid grid-cols-2 gap-2">
                    @foreach(\App\Models\CarPhoto::where('request_id', $auction->request_id)->get() as $photo)
                        <a href="{{ asset('storage/'.$photo->path) }}" class="gallery-item">
                            <img src="{{ asset('storage/'.$photo->path) }}" alt="Фото {{ $loop->iteration }}" class="w-full h-64 object-cover rounded">
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Основная информация -->
            <div class="p-4">
                <h1 class="text-2xl font-bold">{{ ucfirst($auction->brand) }} {{ ucfirst($auction->model) }}, {{ $auction->year }}</h1>
                <div class="flex items-center mt-2">
                    <span class="text-gray-600">Лот #{{ $auction->id }}</span>
                </div>

                <!-- Таймер аукциона -->
                <div class="mt-4 p-4 bg-red-50 rounded-lg">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                            <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                        </svg>
                        <span id="auction-timer" class="text-xl font-semibold text-red-600">
                            {{ $isAuctionEnded ? 'Аукцион завершен' : 'Загрузка...' }}
                        </span>
                    </div>
                    <div class="mt-2 text-sm text-gray-600">
                        Аукцион завершится: {{ \Carbon\Carbon::parse($auction->end_date)->format('d.m.Y H:i') }}
                    </div>
                </div>

                <!-- Текущая ставка -->
                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                    <div class="text-gray-600">Текущая ставка:</div>
                    <div class="text-3xl font-bold text-blue-600" id="current-bid">
                        {{ number_format($auction->current_bid ?? $auction->start_price, 0, '', ' ') }} ₽
                    </div>

                    <!-- Форма ставки -->
                    @if(!$isAuctionEnded)
                        <form id="bid-form" class="mt-4">
                            @csrf
                            <div class="flex">
                                <input type="number" name="amount"
                                       min="{{ $nextBidAmount }}"
                                       step="{{ $auction->bid_step }}"
                                       class="flex-1 p-2 border rounded-l"
                                       placeholder="Ваша ставка (мин. {{ number_format($nextBidAmount, 0, '', ' ') }} ₽)" required>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-r hover:bg-blue-700">
                                    Сделать ставку
                                </button>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                Минимальный шаг ставки: {{ number_format($auction->bid_step, 0, '', ' ') }} ₽
                            </div>
                        </form>
                    @else
                        <div class="mt-4 p-3 bg-gray-100 rounded text-center text-gray-600">
                            Ставки больше не принимаются
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Детали автомобиля -->
        <div class="p-6 border-t">
            <h2 class="text-xl font-semibold mb-4">Характеристики</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-medium text-gray-700">Общая информация</h3>
                    <ul class="mt-2 space-y-2">
                        <li>Год: {{ $auction->year }}</li>
                        <li>Пробег: {{ number_format($auction->mileage, 0, '', ' ') }} км</li>
                        <li>КПП: {{ $auction->kpp == 'akpp' ? 'Автомат' : 'Механика' }}</li>
                        <li>Двигатель: {{ $auction->engine }} л</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-medium text-gray-700">Состояние</h3>
                    <ul class="mt-2 space-y-2">
                        <li>Кузов: {{ $auction->body_condition }}</li>
                        <li>Двигатель: {{ $auction->engine_condition }}</li>
                        <li>Трансмиссия: {{ $auction->transmission_condition }}</li>
                    </ul>
                </div>

                <div class="bg-gray-50 p-4 rounded">
                    <h3 class="font-medium text-gray-700">История</h3>
                    <ul class="mt-2 space-y-2">
                        <li>Дата создания: {{ $auction->created_at->timezone('Europe/Moscow')->format('d.m.Y H:i') }}</li>
                        <li>Дата окончания: {{ \Carbon\Carbon::parse($auction->end_date)->timezone('Europe/Moscow')->format('d.m.Y H:i') }}</li>
                        <li>Начальная цена: {{ number_format($auction->start_price, 0, '', ' ') }} ₽</li>
                        <li>Шаг ставки: {{ number_format($auction->bid_step, 0, '', ' ') }} ₽</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- История ставок -->
        <div class="p-6 border-t">
            <h2 class="text-xl font-semibold mb-4">История ставок</h2>
            <div class="bid-history bg-gray-50 p-4 rounded">
                <div id="bids-container">
                    @foreach($auction->bids()->latest()->take(10)->get() as $bid)
                        <div class="py-2 border-b">
                            <div class="flex justify-between">
                                @php
                                    $user = \App\Models\User::where('id', $bid->user_id)->first();
                                @endphp
                                <span class="font-medium">{{ $user->first_name . ' ' . $user->last_name }}</span>
                                <span class="text-blue-600">{{ number_format($bid->amount, 0, '', ' ') }} ₽</span>
                            </div>
                            <div class="text-sm text-gray-500">{{ $bid->created_at->timezone('Europe/Moscow')->format('d.m.Y H:i') }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Комментарии -->
        <div class="p-6 border-t">
            <h2 class="text-xl font-semibold mb-4">Комментарии</h2>

            <!-- Форма комментария -->
            @if(!$isAuctionEnded)
                <form id="comment-form" class="mb-6">
                    @csrf
                    <textarea name="content" rows="3" class="w-full p-2 border rounded" placeholder="Оставьте ваш комментарий..." required></textarea>
                    <button type="submit" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                        Отправить
                    </button>
                </form>
            @else
                <div class="mb-6 p-3 bg-gray-100 rounded text-center text-gray-600">
                    Комментирование закрыто
                </div>
            @endif

            <!-- Список комментариев -->
            <div id="comments-container" class="space-y-4">
                @foreach($auction->comments()->latest()->get() as $comment)
                    <div class="comment-box p-4 bg-gray-50 rounded">
                        <div class="flex justify-between">
                            @php
                                $user_comment = \App\Models\User::where('id', $comment->user_id)->first();
                            @endphp
                            <span class="font-medium">{{ $user_comment->first_name . ' ' . $user_comment->last_name }}</span>
                            <span class="text-sm text-gray-500">{{ $comment->created_at->timezone('Europe/Moscow')->format('d.m.Y H:i') }}</span>
                        </div>
                        <div class="mt-2">{{ json_decode($comment->content)->content }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Скрипты -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/lightgallery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-zoom/2.7.1/lg-zoom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-fullscreen/2.7.1/lg-fullscreen.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-autoplay/2.7.1/lg-autoplay.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-share/2.7.1/lg-share.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-thumbnail/2.7.1/lg-thumbnail.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-hash/2.7.1/lg-hash.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script>
    // Инициализация галереи с навигационными стрелками
    document.addEventListener('DOMContentLoaded', function() {
        lightGallery(document.getElementById('main-gallery'), {
            selector: '.gallery-item',
            download: false,
            counter: true,
            plugins: [lgZoom, lgFullscreen, lgThumbnail, lgAutoplay, lgShare, lgHash],
            mode: 'lg-slide',
            speed: 500,
            thumbnail: true,
            animateThumb: true,
            showThumbByDefault: false,
            toogleThumb: true,
            nextHtml: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>',
            prevHtml: '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>',
            addClass: 'custom-gallery'
        });
    });

    // Таймер обратного отсчета
    function updateTimer() {
        const endDate = new Date('{{ $auction->end_date }}');
        const now = new Date();
        const diff = endDate - now;

        if (diff <= 0) {
            document.getElementById('auction-timer').textContent = 'Аукцион завершен';
            return;
        }

        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        document.getElementById('auction-timer').textContent =
            `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    @if(!$isAuctionEnded)
    setInterval(updateTimer, 1000);
    updateTimer();
    @endif

    // Отправка ставки (только если аукцион активен)
    @if(!$isAuctionEnded)
    document.getElementById('bid-form').addEventListener('submit', function(e) {
        e.preventDefault();

        axios.post('{{ route('bids.store', $auction->id) }}', {
            amount: this.amount.value
        })
            .then(response => {
                // Обновляем минимальную ставку: текущая ставка + шаг
                const newMinBid = parseInt(response.data.newBid.amount) + parseInt({{ $auction->bid_step }});
                this.amount.min = newMinBid;
                this.amount.placeholder = `Ваша ставка (мин. ${new Intl.NumberFormat('ru-RU').format(newMinBid)} ₽)`;
                this.amount.value = '';
                fetchBids();
                toastr.success('Ваша ставка принята!', 'Успех!');
            })
            .catch(error => {
                toastr.error(error.response.data.message || 'Ошибка при отправке ставки', 'Ошибка!');
            });
    });

    // Отправка комментария (только если аукцион активен)
    document.getElementById('comment-form').addEventListener('submit', function(e) {
        e.preventDefault();

        axios.post('{{ route('comments.store', $auction->id) }}', {
            content: this.content.value
        })
            .then(response => {
                this.content.value = '';
                fetchComments();
                toastr.success('Комментарий добавлен!', 'Успех!');
            })
            .catch(error => {
                toastr.error('Ошибка при отправке комментария', 'Ошибка!');
            });
    });
    @endif

    // Функции для обновления данных в реальном времени (только если аукцион активен)
    function fetchBids() {
        axios.get('{{ route('bids.index', $auction->id) }}')
            .then(response => {
                let bidsHtml = '';
                response.data.forEach(bid => {
                        bidsHtml += `
                        <div class="py-2 border-b">
                            <div class="flex justify-between">
                                <span class="font-medium">${bid.user.first_name} ${bid.user.last_name}</span>
                                <span class="text-blue-600">${new Intl.NumberFormat('ru-RU').format(bid.amount)} ₽</span>
                            </div>
                            <div class="text-sm text-gray-500">${new Date(bid.created_at).toLocaleString('ru-RU')}</div>
                        </div>
                    `;

                });
                document.getElementById('bids-container').innerHTML = bidsHtml;

                // Обновляем текущую ставку и минимальное значение для следующей ставки
                const currentBid = response.data[0]?.amount || {{ $auction->start_price }};
                document.getElementById('current-bid').textContent =
                    new Intl.NumberFormat('ru-RU').format(currentBid) + ' ₽';

                if (!{{ $isAuctionEnded ? 'true' : 'false' }}) {
                    const bidForm = document.getElementById('bid-form');
                    if (bidForm) {
                        const newMinBid = parseInt(currentBid) + parseInt({{ $auction->bid_step }});
                        bidForm.amount.min = newMinBid;
                        bidForm.amount.placeholder = `Ваша ставка (мин. ${new Intl.NumberFormat('ru-RU').format(newMinBid)} ₽)`;
                    }
                }
            });
    }

    function fetchComments() {
        axios.get('{{ route('comments.index', $auction->id) }}')
            .then(response => {
                let commentsHtml = '';
                response.data.forEach(comment => {
                        commentsHtml += `
                        <div class="comment-box p-4 bg-gray-50 rounded">
                            <div class="flex justify-between">
                                <span class="font-medium">${comment.user.last_name} ${comment.user.first_name}</span>
                                <span class="text-sm text-gray-500">${new Date(comment.created_at).toLocaleString('ru-RU')}</span>
                            </div>
                            <div class="mt-2">${comment.content}</div>
                        </div>
                    `;
                });
                document.getElementById('comments-container').innerHTML = commentsHtml;
            });
    }

    // Обновление данных каждые 5 секунд (только если аукцион активен)
    @if(!$isAuctionEnded)
    setInterval(() => {
        fetchBids();
        updateTimer();
    }, 5000);
    @endif
</script>
</body>
</html>
