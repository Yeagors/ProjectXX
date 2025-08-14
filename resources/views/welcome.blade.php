<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выкуп авто</title>
    <style>
        .estimate-value {
            display: none; /* Скрываем по умолчанию */
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
            text-align: center;
            margin: 1.5rem 0;
            padding: 1rem;
            background-color: rgba(79, 195, 247, 0.1);
            border-radius: 8px;
            border: 1px dashed var(--accent-color);
        }

        .calculate-btn {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 1rem auto 0;
            padding: 1rem;
            background-color: var(--accent-color);
            color: var(--text-dark);
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .calculate-btn:hover {
            background-color: #3ab4e0;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-dark);
            color: var(--text-light);
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: var(--bg-darker);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
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

        .main-content {
            display: flex;
            flex-direction: column;
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
            gap: 2rem;
        }

        .process-row {
            display: flex;
            gap: 2rem;
        }

        .process-steps-horizontal {
            display: flex;
            gap: 1rem;
            width: 100%;
            margin-bottom: 2rem;
        }

        .step-horizontal {
            flex: 1;
            padding: 1.5rem;
            background-color: rgba(79, 195, 247, 0.1);
            border-radius: 8px;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-height: 150px;
        }

        .step-horizontal:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .step-number-horizontal {
            width: 40px;
            height: 40px;
            background-color: var(--accent-color);
            color: var(--text-dark);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }

        .step-content-horizontal h3 {
            margin: 0 0 0.5rem 0;
            color: var(--accent-color);
            font-size: 1.1rem;
        }

        .step-content-horizontal p {
            margin: 0;
            color: var(--text-light);
            font-size: 0.9rem;
        }

        .content-row {
            display: flex;
            gap: 2rem;
        }

        .form-container {
            flex: 1;
            position: relative;
            max-width: 30%;
        }

        .form-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--accent-color);
            animation: slideIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateX(50px);
            height: 100%;
        }

        @keyframes slideIn {
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .form-title {
            margin-top: 0;
            margin-bottom: 2rem;
            color: var(--accent-color);
            text-align: center;
            font-size: 1.8rem;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--text-light);
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #333;
            border-radius: 6px;
            background-color: #2a2a2a;
            color: var(--text-light);
            font-size: 1rem;
            transition: border-color 0.3s;
            height: 44px; /* Добавляем фиксированную высоту */
            box-sizing: border-box; /* Учитываем padding в общей высоте */
        }

        /* Для числового поля года, чтобы убрать стрелки в Chrome/Safari */
        .form-group input[type="number"] {
            -moz-appearance: textfield;
        }
        .form-group input[type="number"]::-webkit-outer-spin-button,
        .form-group input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .form-group input.error {
            border-color: #ff4444;
        }

        .error-message {
            color: #ff4444;
            font-size: 0.8rem;
            margin-top: 0.3rem;
            display: none;
        }

        .estimate-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--accent-color);
            text-align: center;
            margin: 1.5rem 0;
            padding: 1rem;
            background-color: rgba(79, 195, 247, 0.1);
            border-radius: 8px;
            border: 1px dashed var(--accent-color);
        }

        .submit-btn {
            display: block;
            width: 100%;
            max-width: 300px;
            margin: 2rem auto 0;
            padding: 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
        }

        .submit-btn:hover {
            background-color: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }


        .video-placeholder iframe {
            width: 100%;
            height: 100%;
            border: none;
        }


        @media (max-width: 1200px) {
            .process-steps-horizontal {
                flex-wrap: wrap;
            }

            .step-horizontal {
                min-width: calc(50% - 1rem);
            }

            .content-row {
                flex-direction: column;
            }

            .form-container {
                max-width: 100%;
            }

            .video-placeholder {
                height: 400px;
                margin-top: 2rem;
            }
        }

        @media (max-width: 768px) {
            .step-horizontal {
                min-width: 100%;
            }
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<header>
    <div class="logo">Выкуп авто</div>
    @include('components.nav-menu')
</header>

<div class="main-content">
    <div class="process-steps-horizontal">
        <div class="step-horizontal">
            <div class="step-number-horizontal">1</div>
            <div class="step-content-horizontal">
                <h3>Оставляете заявку</h3>
                <p>Заполните простую форму, и мы свяжемся с вами в течение 15 минут</p>
            </div>
        </div>
        <div class="step-horizontal">
            <div class="step-number-horizontal">2</div>
            <div class="step-content-horizontal">
                <h3>Бесплатный осмотр</h3>
                <p>Специалист приедет в удобное для вас место</p>
            </div>
        </div>
        <div class="step-horizontal">
            <div class="step-number-horizontal">3</div>
            <div class="step-content-horizontal">
                <h3>Быстрый аукцион</h3>
                <p>30-минутный аукцион среди покупателей</p>
            </div>
        </div>
        <div class="step-horizontal">
            <div class="step-number-horizontal">4</div>
            <div class="step-content-horizontal">
                <h3>Получаете деньги</h3>
                <p>Наличными или переводом сразу после сделки</p>
            </div>
        </div>
    </div>

    <div class="content-row">
        <div class="form-container">
            <div class="form-card">
                <h1 class="form-title">Оценка вашего автомобиля</h1>

                <form id="carForm">
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="brand">Марка автомобиля</label>
                            <select id="brand" name="brand" required>
                                <option value="">Выберите марку</option>
                                @foreach ($brands as $brandKey => $brandModels)
                                <option value="{{$brandKey}}">{{ucfirst($brandKey)}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="model">Модель</label>
                            <select id="model" name="model" required>
                                <option value="">Сначала выберите марку</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="kpp">КПП</label>
                            <select id="kpp" name="kpp" required>
                                <option value="mkpp">МКПП</option>
                                <option value="akpp">АКПП</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="license_plate">Госномер</label>
                            <input type="text" id="license_plate" name="license_plate" placeholder="А123БВ777" required>
                            <div class="error-message" id="plate-error">Введите корректный российский номер (пример: А123АА777)</div>
                        </div>

                        <div class="form-group">
                            <label for="year">Год выпуска</label>
                            <input type="number" id="year" name="year" min="1990" max="{{ date('Y') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="user_phone">Номер телефона</label>
                            <input type="text" id="user_phone" name="user_phone" placeholder="+7 (999) 123-45-67" required>
                            <div class="phone-error" id="phone-error">Введите корректный номер телефона (пример: +79991234567 или 89991234567)</div>
                        </div>

                        <div class="form-group">
                            <label for="user_name">ФИО</label>
                            <input type="text" id="user_name" name="user_name" placeholder="Иванов Иван Иванович" required>
                            <div class="name-error" id="name-error">ФИО может содержать только буквы и пробелы</div>
                        </div>
                        <button type="button" class="calculate-btn" id="calculateBtn">Рассчитать стоимость</button>
                        <div class="estimate-value" id="estimateValue">
                            Примерная оценка: <span id="estimate">~</span> ₽
                        </div>

                        <button type="button" class="submit-btn" id="submitBtn">Отправить заявку</button>
                    </div>
                </form>
            </div>
        </div>


    </div>
</div>

<script>
    // Обработчик кнопки "Рассчитать стоимость"
    document.getElementById('calculateBtn').addEventListener('click', function() {
        const form = document.getElementById('carForm');
        const plateInput = document.getElementById('license_plate');
        const phoneInput = document.getElementById('user_phone');
        const nameInput = document.getElementById('user_name');

        // Проверяем валидность формы
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Проверяем дополнительные валидации
        let isValid = true;

        // Проверка номера
        const plateRegex = /^[АВЕКМНОРСТУХ]\d{3}[АВЕКМНОРСТУХ]{2}\d{2,3}$|^\d{4}[АВЕКМНОРСТУХ]{2}\d{2}$/;
        if (!plateRegex.test(plateInput.value)) {
            plateInput.classList.add('error');
            document.getElementById('plate-error').style.display = 'block';
            isValid = false;
        }

        // Проверка телефона
        const phoneRegex = /^(\+7|8)\d{10}$/;
        const phoneDigits = phoneInput.value.replace(/\D/g, '');
        if (!phoneRegex.test(phoneDigits)) {
            phoneInput.classList.add('error');
            document.getElementById('phone-error').style.display = 'block';
            isValid = false;
        }

        // Проверка ФИО
        const nameRegex = /^[а-яА-ЯёЁ\s]+$/;
        if (!nameRegex.test(nameInput.value)) {
            nameInput.classList.add('error');
            document.getElementById('name-error').style.display = 'block';
            isValid = false;
        }

        if (!isValid) return;

        // Если все проверки пройдены, отправляем AJAX-запрос для расчета стоимости
        let pageData = $('#carForm').serialize();

        // Показываем индикатор загрузки
        const calculateBtn = document.getElementById('calculateBtn');
        calculateBtn.disabled = true;
        calculateBtn.textContent = 'Рассчитываем...';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("calculatePrice") }}',
            dataType: 'json',
            data: {
                brand: $('#brand').val(),
                model: $('#model').val(),
                kpp: $('#kpp').val(),
                year: $('#year').val(),
                license_plate: $('#license_plate').val()
            },
            success: function(data) {
                calculateBtn.disabled = false;
                calculateBtn.textContent = 'Рассчитать стоимость';

                if(data && data.estimate) {
                    document.getElementById('estimateValue').style.display = 'block';
                    document.getElementById('estimate').textContent = data.estimate;
                    document.getElementById('submitBtn').style.display = 'block';
                    document.getElementById('calculateBtn').style.display = 'none';
                } else {
                    alert('Не удалось рассчитать стоимость. Пожалуйста, проверьте введенные данные.');
                }
            },
            error: function() {
                calculateBtn.disabled = false;
                calculateBtn.textContent = 'Рассчитать стоимость';
                alert('Произошла ошибка при расчете стоимости. Пожалуйста, попробуйте позже.');
            }
        });
    });

    // Обработчик кнопки "Отправить заявку"
    document.getElementById('submitBtn').addEventListener('click', function() {
        // Ваш существующий код отправки формы
        let pageData = $('#carForm').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("addRequest") }}',
            dataType: 'json',
            data: pageData,
            success: function(data) {
                if(data) {
                    window.location.reload();
                    alert('Заявка успешно отправлена!');
                }
            },
        });
    });

    // Изначально скрываем кнопку отправки
    document.getElementById('submitBtn').style.display = 'none';
    // Валидация российского номерного знака
    document.getElementById('license_plate').addEventListener('input', function(e) {
        // Автоматическое преобразование букв в заглавные
        this.value = this.value.toUpperCase();

        const plate = this.value;
        const errorElement = document.getElementById('plate-error');
        const regex = /^[АВЕКМНОРСТУХ]\d{3}[АВЕКМНОРСТУХ]{2}\d{2,3}$|^\d{4}[АВЕКМНОРСТУХ]{2}\d{2}$/;

        if (plate && !regex.test(plate)) {
            this.classList.add('error');
            errorElement.style.display = 'block';
        } else {
            this.classList.remove('error');
            errorElement.style.display = 'none';
        }
    });

    // Валидация номера телефона
    document.getElementById('user_phone').addEventListener('input', function(e) {
        const phone = this.value.replace(/\D/g, ''); // Удаляем все нецифровые символы
        const errorElement = document.getElementById('phone-error');
        const regex = /^(\+7|8)\d{10}$/;

        // Форматирование телефона
        if (phone.length > 1) {
            let formattedPhone = '';
            if (phone.startsWith('8')) {
                formattedPhone = '8 (' + phone.substring(1, 4) + ') ' + phone.substring(4, 7) + '-' + phone.substring(7, 9) + '-' + phone.substring(9, 11);
            } else if (phone.startsWith('7')) {
                formattedPhone = '+7 (' + phone.substring(1, 4) + ') ' + phone.substring(4, 7) + '-' + phone.substring(7, 9) + '-' + phone.substring(9, 11);
            }
            this.value = formattedPhone;
        }

        if (phone && !regex.test(phone)) {
            this.classList.add('error');
            errorElement.style.display = 'block';
        } else {
            this.classList.remove('error');
            errorElement.style.display = 'none';
        }
    });

    // Валидация ФИО (только буквы и пробелы)
    document.getElementById('user_name').addEventListener('input', function(e) {
        this.value = this.value.replace(/[^а-яА-ЯёЁ\s]/g, ''); // Удаляем все, кроме букв и пробелов
        const errorElement = document.getElementById('name-error');

        if (this.value && !/^[а-яА-ЯёЁ\s]+$/.test(this.value)) {
            this.classList.add('error');
            errorElement.style.display = 'block';
        } else {
            this.classList.remove('error');
            errorElement.style.display = 'none';
        }
    });


        document.getElementById('brand').addEventListener('change', function() {
        const brand = this.value;
        const modelSelect = document.getElementById('model');

        // Правильный способ передачи PHP-массива в JavaScript
        const brandsData = <?php echo json_encode($brands, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

        modelSelect.innerHTML = '<option value="">Выберите модель</option>';

        if (brand && brandsData[brand]) {
        brandsData[brand].forEach(function(model) {
        const option = document.createElement('option');
        option.value = model.toLowerCase().replace(/\s+/g, '-');
        option.textContent = model;
        modelSelect.appendChild(option);
            });
        }
    });
</script>
</body>
</html>
