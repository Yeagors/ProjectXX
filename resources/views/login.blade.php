<!DOCTYPE html>
<html>
<style>

    body {
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: sans-serif;
        line-height: 1.5;
        min-height: 100vh;
        background: #f3f3f3;
        flex-direction: column;
        margin: 0;
    }

    .main {
        background-color: #fff;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        padding: 10px 20px;
        transition: transform 0.2s;
        width: 500px;
        text-align: center;
    }

    h1 {
        color: #4CAF50;
    }

    label {
        display: block;
        width: 100%;
        margin-top: 10px;
        margin-bottom: 5px;
        text-align: left;
        color: #555;
        font-weight: bold;
    }

    input {
        display: block;
        width: 100%;
        margin-bottom: 15px;
        padding: 10px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    button {
        padding: 15px;
        border-radius: 10px;
        margin-top: 15px;
        margin-bottom: 15px;
        border: none;
        color: white;
        cursor: pointer;
        background-color: #4CAF50;
        width: 100%;
        font-size: 16px;
    }

    .wrap {
        display: flex;
        justify-content: center;
        align-items: center;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <link rel="stylesheet" href="style.css">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<body>
<div class="main">
    <h1>Выкуп авто</h1>

    <form id="entryForm">
        <label for="user_phone">
            Номер телефона:
        </label>
        <input type="text" id="user_phone" name="user_phone"
               placeholder="Введите свой номер телефона" required>

        <label for="password">
            Пароль:
        </label>
        <input type="password" id="password" name="password"
               placeholder="Введите пароль" required>

        <div class="wrap">
            <button type="button" class="entry-btn" id="entry">
                Войти
            </button>
        </div>
    </form>

    <p>Еще нет аккаунта?
        <button type="submit" onclick="window.location.href = '{{ route('registration') }}';">
            Зарегистрироваться
        </button>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
</body>
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
    document.getElementById('entry').addEventListener('click', function() {
        // Ваш существующий код отправки формы
        let pageData = $('#entryForm').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("auth") }}',
            dataType: 'json',
            data: pageData,
            success: function(data) {
                if(data === 401) {
                    toastr.error('Неверный номер телефона или пароль!', 'Ошибка!')
                } else if(data === 404) {
                    toastr.error('Пользователя с таким номером телефона не сущетсвует!', 'Ошибка!')
                } else {
                    window.location.href = "{{ route('dashboard') }}";
                }

            },
        });
    });
</script>
</html>
