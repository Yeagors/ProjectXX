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

    /* Если нужно сделать кнопки более заметными */
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
        <li><button onclick="navigateToAuction()">Аукцион</button></li>
        <li><button onclick="navigateToInfo()">Информация</button></li>
        <li><button onclick="navigateToBlog()">Блог</button></li>
        <li><button onclick="navigateToContacts()">Контакты</button></li>
        <li><button onclick="navigateToLogin()">Вход</button></li>
    </ul>

    <script>
        // Базовая функция для навигации
        function navigateTo(routeName) {
            window.location.href = "{{ route('fp') }}".replace('fp', routeName);
        }

        // Вы можете добавить индивидуальные обработчики для каждой кнопки
        function navigateToAuction() {
            // Ваша логика для аукциона
        }

        function navigateToInfo() {
            // Ваша логика для информации
        }

        function navigateToBlog() {
            // Ваша логика для блога
        }

        function navigateToContacts() {
            // Ваша логика для контактов
        }

        function navigateToLogin() {
            window.location.href = "{{ route('showLoginForm') }}";
        }
    </script>
</nav>
