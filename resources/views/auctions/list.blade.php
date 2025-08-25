<!DOCTYPE html>
<html lang="ru">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/css/lightgallery.min.css">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Аукционы автомобилей</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .auction-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .auction-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 700px));
            gap: 1.5rem;
        }

        .auction-item {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .auction-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .auction-image-container {
            position: relative;
            height: 250px;
            overflow: hidden;
        }

        .auction-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .auction-item:hover .auction-image {
            transform: scale(1.05);
        }

        .auction-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(0,0,0,0.7);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
        }

        .auction-details {
            padding: 1.25rem;
        }

        .auction-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #333;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .auction-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #2e7d32;
            margin-bottom: 1rem;
        }

        .auction-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .meta-item {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #666;
        }

        .meta-item svg {
            margin-right: 0.5rem;
            width: 16px;
            height: 16px;
            fill: #666;
        }

        .auction-location {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }

        .auction-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 1rem;
            border-top: 1px solid #eee;
        }

        .auction-date {
            font-size: 0.8rem;
            color: #999;
        }

        .auction-link {
            display: block;
            text-decoration: none;
            color: inherit;
        }

        .sort-controls {
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .sort-btn {
            background: none;
            border: 1px solid #ddd;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sort-btn.active {
            background: #1976d2;
            color: white;
            border-color: #1976d2;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 2rem;
            gap: 0.5rem;
        }

        .page-item {
            padding: 0.5rem 1rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .page-item.active {
            background: #1976d2;
            color: white;
            border-color: #1976d2;
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
<div class="auction-container">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Активные аукционы</h1>

    <div class="auction-list">
        @foreach($auctions as $auction)
            <a href="{{ route('auctions.show', $auction->id) }}" class="auction-link">
                <div class="auction-item">
                    <div class="auction-image-container">
                        <a href="{{ asset('storage/' . (\App\Models\CarPhoto::where('request_id', $auction->request_id)->first()->path ?? '/images/no-photo.jpg')) }}"
                           class="gallery-item"
                           data-lg-size="1200-800"
                           data-src="{{ asset('storage/' . (\App\Models\CarPhoto::where('request_id', $auction->request_id)->first()->path ?? '/images/no-photo.jpg')) }}"
                        >
                            <img src="{{ asset('storage/' . (\App\Models\CarPhoto::where('request_id', $auction->request_id)->first()->path ?? '/images/no-photo.jpg')) }}"
                                 alt="{{ $auction->brand }} {{ $auction->model }}"
                                 class="auction-image">
                        </a>
                        <span class="auction-badge">Аукцион</span>
                    </div>
                    <div class="auction-details" onclick="window.location='{{ route('auctions.show', $auction->id) }}'">
                        <h3 class="auction-title">{{ ucfirst($auction->brand) }} {{ ucfirst($auction->model) }}, {{ $auction->year }}</h3>
                        <div class="auction-price">{{ number_format($auction->start_price, 0, '', ' ') }} ₽</div>

                        <div class="auction-meta">
                            <span class="meta-item">
                                <svg viewBox="0 0 24 24">
                                    <path d="M5,13l1.5-4.5h11L19,13M15,5h1a1,1 0 0,1 1,1v4a1,1 0 0,1 -1,1h-1M6,5h1a1,1 0 0,1 1,1v4a1,1 0 0,1 -1,1H6M6,10v4m3-4v4m3-4v4m3-4v4m-9,3a1,1 0 0,0 -1,1a1,1 0 0,0 1,1h10a1,1 0 0,0 1-1a1,1 0 0,0 -1-1H3z"/>
                                </svg>
                                {{ $auction->kpp == 'akpp' ? 'Автомат' : 'Механика' }}
                            </span>
                            <span class="meta-item">
                                <svg viewBox="0 0 24 24">
                                    <path d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11A1.5,1.5 0 0,1 10.5,9.5A1.5,1.5 0 0,1 12,8A1.5,1.5 0 0,1 13.5,9.5A1.5,1.5 0 0,1 12,11Z"/>
                                </svg>
                                {{ number_format($auction->mileage, 0, '', ' ') }} км
                            </span>
                        </div>

                        <div class="auction-footer">
                            <span class="auction-date">{{ $auction->created_at->format('d-m-Y') }}</span>
                            <span class="auction-timer">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="#e53935">
                                    <path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/>
                                    <path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/>
                                </svg>
                                <span class="timer-value" data-end-date="{{ $auction->end_date }}">
                                    @php
                                        $now = now()->timezone('Europe/Moscow')->format('d.m.Y H:i');
                                        $endDate = \Carbon\Carbon::parse($auction->end_date);
                                        $diff = $endDate->diff($now);

                                        if ($now > $endDate) {
                                            echo 'Аукцион завершен';
                                        } else {
                                            echo 'Осталось: ' . $diff->format('%d дн %h ч %i мин');
                                        }
                                    @endphp
                                </span>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const timerElement = document.querySelector('.timer-value');
                                    const endDateString = timerElement.getAttribute('data-end-date');
                                    const endDate = new Date(endDateString);

                                    function updateTimer() {
                                        const now = new Date();
                                        const diffMs = endDate - now;

                                        if (diffMs <= 0) {

                                            timerElement.textContent = 'Аукцион завершен';
                                            return;
                                        }

                                        const diffMins = Math.floor(diffMs / (1000 * 60));
                                        timerElement.textContent = `Осталось: ${diffMins} минут`;
                                    }

                                    // Обновляем сразу при загрузке
                                    updateTimer();

                                    // Обновляем каждую минуту
                                    setInterval(updateTimer, 60000);
                                });
                                </script>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <div class="pagination">
        @if ($auctions->currentPage() > 1)
            <a href="{{ $auctions->previousPageUrl() }}" class="page-item">Назад</a>
        @endif

        @for ($i = 1; $i <= $auctions->lastPage(); $i++)
            <a href="{{ $auctions->url($i) }}" class="page-item {{ $auctions->currentPage() == $i ? 'active' : '' }}">{{ $i }}</a>
        @endfor

        @if ($auctions->hasMorePages())
            <a href="{{ $auctions->nextPageUrl() }}" class="page-item">Вперед</a>
        @endif
    </div>
</div>


<!-- Добавляем необходимые JS библиотеки -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightgallery/2.7.1/lightgallery.min.js"></script>
<!-- Плагины для lightgallery (опционально) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-zoom/2.7.1/lg-zoom.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lg-fullscreen/2.7.1/lg-fullscreen.min.js"></script>
    <script>

        function sortAuctions(sortBy) {
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sortBy);
        window.location.href = url.toString();
    }

        // Инициализация lightgallery
        document.addEventListener('DOMContentLoaded', function() {
        // Для каждого аукциона инициализируем галерею
        $('.auction-item').each(function() {
            lightGallery($(this).find('.gallery-item')[0], {
                selector: '.gallery-item',
                download: false,
                counter: false,
                plugins: [lgZoom, lgFullscreen]
            });
        });
            document.querySelectorAll('.gallery-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault(); // Предотвращаем переход по ссылке
                    lightGallery(item, {
                        selector: '.gallery-item',
                        download: false,
                        counter: false,
                        plugins: [lgZoom, lgFullscreen]
                    });
                });
            });
        // Или альтернативный вариант для всей страницы
        // lightGallery(document.querySelectorAll('.gallery-item'), {
        //     selector: '.gallery-item',
        //     download: false,
        //     counter: false,
        //     plugins: [lgZoom, lgFullscreen]
        // });
    });

</script>
</body>
</html>
