<style>
    .nav-buttons {
        display: flex;
        gap: 1.5rem;
        list-style: none;
        margin: 0;
        padding: 0;
        align-items: center;
    }

    .nav-buttons button {
        background: none;
        border: none;
        color: var(--text-light);
        font-weight: 500;
        font-size: 1rem;
        font-family: inherit;
        cursor: pointer;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
        border-radius: 6px;
    }

    .nav-buttons button:hover {
        color: var(--accent-color);
        background-color: rgba(79, 195, 247, 0.1);
        transform: translateY(-2px);
    }

    .nav-buttons button:focus {
        outline: none;
        box-shadow: 0 0 0 2px var(--accent-color);
    }

    .nav-buttons li:last-child button {
        background-color: var(--primary-color);
        color: white;
        padding: 0.5rem 1.5rem;
    }

    .nav-buttons li:last-child button:hover {
        background-color: var(--secondary-color);
    }
</style>

<nav class="nav-menu">
    <ul class="nav-buttons">
        <li><button onclick="navigateTo('fp')">Главная</button></li>
        @if(Auth::user() !== null && in_array(Auth::user()->phone, ['+79817754640', '+79992199737', '+79967867340']))
            <li><button onclick="requestsToAuction()">Заявки на осмотр</button></li>
        @endif
        @if(Auth::user() !== null && in_array(Auth::user()->phone, ['+79817754640', '+79992199737', '+79967867340']))
            <li><button onclick="requestsToReview()">Заявки в работе</button></li>
        @endif
        @if(Auth::user() !== null && in_array(Auth::user()->phone, ['+79817754640', '+79992199737', '+79967867340']))
            <li><button onclick="requestsComplete()">Обработанные заявки</button></li>
        @endif
        <li><button onclick="navigateToAuction()">Аукцион</button></li>
        <li><button onclick="navigateToInfo()">Информация</button></li>
        <li><button onclick="navigateToBlog()">Блог</button></li>
        <li><button onclick="navigateToContacts()">Контакты</button></li>
        @auth
            <li>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="button" onclick="document.getElementById('logout-form').submit()">
                        Выход {{ Auth::user()->name }}
                    </button>
                </form>
            </li>
        @else
            <li><button onclick="navigateToLogin()">Вход</button></li>
        @endauth
    </ul>
</nav>

<script>
    // Базовая функция для навигации
    function navigateTo(routeName) {
        window.location.href = "{{ route('dashboard') }}".replace('dashboard', routeName);
    }

    function navigateToLogin() {
        window.location.href = "{{ route('login') }}";
    }

    function requestsToAuction() {
        window.location.href = "{{ route('requests.auction') }}";
    }
    function requestsToReview() {
        window.location.href = "{{ route('requests.review') }}";
    }
    function requestsComplete() {
        window.location.href = "{{ route('requests.complete') }}";
    }

    // Остальные функции навигации
    function navigateToAuction() {
        window.location.href = "{{ route('auctions.index') }}";
    }

    function navigateToInfo() {
        // Логика для информации
    }

    function navigateToBlog() {
        // Логика для блога
    }

    function navigateToContacts() {
        // Логика для контактов
    }
</script>
