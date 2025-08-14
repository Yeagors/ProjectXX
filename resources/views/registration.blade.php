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
        padding: 20px 30px;
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

    input, select {
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

    .login-link {
        margin-top: 10px;
        color: #555;
    }

    .login-link a {
        color: #4CAF50;
        text-decoration: none;
        font-weight: bold;
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

<head>
    <link rel="stylesheet" href="style.css">
</head>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<body>
<div class="main">
    <h1>Регистрация</h1>

    <form id="regForm">
        <label for="last_name">
            Фамилия:
        </label>
        <input type="text" id="last_name" name="last_name"
               placeholder="Введите фамилию" required>

        <label for="first_name">
            Имя:
        </label>
        <input type="text" id="first_name" name="first_name"
               placeholder="Введите имя" required>

        <label for="middle_name">
            Отчество:
        </label>
        <input type="text" id="middle_name" name="middle_name"
               placeholder="Введите отчество">

        <label for="bd">
            Дата рождения:
        </label>
        <input type="date" id="bd" name="bd" required>

        <label for="phone">
            Номер телефона:
        </label>
        <input type="tel" id="user_phone" name="user_phone"
               placeholder="Введите номер телефона" required>

        <label for="role">
            Роль:
        </label>
        <select id="role" name="role" required>
            <option value="" disabled selected>Выберите роль</option>
            <option value="buyer">Покупатель</option>
            <option value="seller">Продавец</option>
        </select>

        <label for="password">
            Пароль:
        </label>
        <input type="password" id="password" name="password"
               placeholder="Введите пароль" required>
        <div class="wrap">
            <button type="button" class="submit-btn" id="submit">
                Зарегистрироваться
            </button>
        </div>
    </form>

</div>
</body>
<script>
    document.getElementById('submit').addEventListener('click', function() {
        // Ваш существующий код отправки формы
        let pageData = $('#regForm').serialize();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("setRegistration") }}',
            dataType: 'json',
            data: pageData,
            success: function(data) {
                if(data === 200) {
                    window.location.href = '{{ route('login') }}';
                } else {
                    if(data === 500) {
                        alert('Произошел технический сбой, повторите попытку позже!')
                    } else if(data === 401) {
                        alert('Пользователь с таким номером телефона уже зарегистрирован!')
                    }
                }
            },
        });
    });
</script>
</html>
