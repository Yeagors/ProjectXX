<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Выкуп авто</title>
    <!-- Подключаем Cropper.js CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
    <!-- Подключаем Dropzone.js CSS -->
    <link href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" rel="stylesheet">
    <style>
        .photo-upload-container {
            margin-bottom: 1.5rem;
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
        .photo-upload-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 0.75rem;
            background-color: rgba(79, 195, 247, 0.1);
            border: 2px dashed var(--accent-color);
            border-radius: 6px;
            color: var(--accent-color);
            cursor: pointer;
            transition: all 0.3s;
        }

        .photo-upload-btn:hover {
            background-color: rgba(79, 195, 247, 0.2);
        }

        .photo-upload-btn i {
            margin-right: 8px;
            font-size: 1.2rem;
        }

        .photo-preview-container {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .photo-preview {
            position: relative;
            width: 80px;
            height: 80px;
            border-radius: 4px;
            overflow: hidden;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-preview .remove-photo {
            position: absolute;
            top: 2px;
            right: 2px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }

        .hidden-file-input {
            display: none;
        }
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
        .compact-form .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .compact-form .form-group {
            margin-bottom: 0.5rem;
        }

        .compact-form .form-group label {
            font-size: 0.9rem;
            margin-bottom: 0.3rem;
        }

        .compact-form .form-group input,
        .compact-form .form-group select {
            padding: 0.5rem;
            font-size: 0.9rem;
            height: 38px;
        }

        .compact-form .form-title {
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .compact-form .buttons-row {
            grid-column: span 2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        /* Стили для Select2 */
        .select2-container--default .select2-selection--single {
            height: 38px;
            border: 1px solid #333;
            border-radius: 6px;
            background-color: #2a2a2a;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #e0e0e0;
            line-height: 38px;
            padding-left: 12px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #4fc3f7;
            color: #121212;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #2a2a2a;
            color: #4fc3f7;
        }

        .select2-dropdown {
            background-color: #2a2a2a;
            border: 1px solid #333;
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
            max-width: 40%;
        }

        .profile-container {
            flex: 1;
            position: relative;
            max-width: 60%;
        }
        .form-container, .profile-container {
            height: auto;
            min-height: min-content;
        }

        .content-row {
            align-items: flex-start;
        }

        .form-card, .profile-card {
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
        /* Стили для Dropzone */
        #carPhotosDropzone {
            border: 2px dashed var(--accent-color);
            border-radius: 8px;
            padding: 20px;
            background-color: rgba(79, 195, 247, 0.1);
            min-height: 150px;
        }

        #carPhotosDropzone .dz-preview {
            margin: 10px;
        }

        #carPhotosDropzone .dz-preview .dz-image {
            width: 120px;
            height: 120px;
            border-radius: 4px;
        }

        #carPhotosDropzone .dz-preview .dz-details {
            padding: 0.5em;
        }

        #carPhotosDropzone .dz-preview .dz-remove {
            color: #ff4444;
            margin-top: 5px;
            text-decoration: none;
        }

        #carPhotosDropzone .dz-message {
            color: var(--accent-color);
            font-size: 1rem;
            margin: 2em 0;
        }
        .form-title {
            margin-top: 0;
            margin-bottom: 2rem;
            color: var(--accent-color);
            text-align: center;
            font-size: 1.8rem;
        }

        .profile-title {
            margin-top: 0;
            margin-bottom: 1.5rem;
            color: var(--accent-color);
            text-align: center;
            font-size: 1.5rem;
        }

        .form-grid {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .profile-info {
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

        /* Стили для профиля */
        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 3px solid var(--accent-color);
            display: block;
        }

        .profile-status {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 8px;
        }

        .status-online {
            background-color: #4CAF50;
            box-shadow: 0 0 8px #4CAF50;
        }

        .status-offline {
            background-color: #F44336;
        }

        .profile-detail {
            margin-bottom: 1rem;
        }

        .profile-detail-label {
            font-size: 0.9rem;
            color: #aaa;
            margin-bottom: 0.3rem;
        }

        .profile-detail-value {
            font-size: 1rem;
            color: var(--text-light);
            padding: 0.5rem;
            background-color: #2a2a2a;
            border-radius: 4px;
        }

        .profile-role {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            background-color: var(--accent-color);
            color: var(--text-dark);
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.8rem;
            margin-top: 0.5rem;
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

            .form-container, .profile-container {
                max-width: 100%;
            }


        }
        .avatar-edit-container {
            position: relative;
            width: 120px;
            margin: 0 auto 1.5rem;
        }

        .avatar-edit-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: var(--accent-color);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }

        .avatar-edit-overlay:hover {
            transform: scale(1.1);
        }

        .avatar-edit-overlay i {
            color: var(--text-dark);
            font-size: 18px;
        }

        /* Модальное окно для обрезки */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
        }

        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: var(--card-bg);
            padding: 20px;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .modal-title {
            color: var(--accent-color);
            font-size: 1.2rem;
        }

        .close-modal {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: var(--text-light);
        }

        .image-container {
            width: 100%;
            height: 400px;
            margin-bottom: 15px;
        }

        #image-to-crop {
            max-width: 100%;
            max-height: 100%;
        }

        .modal-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .modal-btn {
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            border: none;
            font-weight: bold;
        }

        .cancel-btn {
            background-color: #6c757d;
            color: white;
        }
        .content-row {
            display: flex;
            gap: 2rem;
            align-items: stretch; /* Добавлено для выравнивания по высоте */
        }

        .form-container, .profile-container {
            flex: 1;
            position: relative;
            display: flex; /* Добавлено */
            flex-direction: column; /* Добавлено */
        }

        .form-card, .profile-card {
            background-color: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            border: 2px solid var(--accent-color);
            animation: slideIn 0.8s ease-out forwards;
            opacity: 0;
            transform: translateX(50px);
            flex-grow: 1; /* Добавлено - растягивает карточку на всю доступную высоту */
            display: flex; /* Добавлено */
            flex-direction: column; /* Добавлено */
        }
        .save-btn {
            background-color: var(--accent-color);
            color: var(--text-dark);
        }

        /* Dropzone стили */
        .dropzone {
            border: 2px dashed var(--accent-color);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 15px;
            background-color: rgba(79, 195, 247, 0.1);
        }

        .dropzone .dz-message {
            margin: 0;
            color: var(--accent-color);
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
                <div class="form-card compact-form">
                    <h1 class="form-title">Оценка авто</h1>

                    <form id="carForm">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="brand">Марка</label>
                                <select id="brand" name="brand" class="select2" required>
                                    <option value="">Выберите марку</option>
                                    @foreach ($brands as $brandKey => $brandModels)
                                        <option value="{{$brandKey}}">{{ucfirst($brandKey)}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="model">Модель</label>
                                <select id="model" name="model" class="select2" required>
                                    <option value="">Сначала выберите марку</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="year">Год</label>
                                <input type="number" id="year" name="year" min="1990" max="{{ date('Y') }}" maxlength="4" required>
                            </div>

                            <div class="form-group">
                                <label for="kpp">КПП</label>
                                <select id="kpp" name="kpp" class="select2" required>
                                    <option value="mkpp">МКПП</option>
                                    <option value="akpp">АКПП</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="license_plate">Госномер</label>
                                <input type="text" id="license_plate" name="license_plate" placeholder="А123АВ777" required>
                                <div class="error-message" id="plate-error">Введите корректный номер</div>
                            </div>
                            <div class="form-group">
                                <label for="millage">Пробег авто</label>
                                <input type="text" id="millage" name="millage" placeholder="20000">
                            </div>
                            <div class="form-group">
                                <label for="phone">Телефон</label>
                                <input type="text" id="phone" name="phone" value="{{Auth::user()->phone ?? ''}}" placeholder="+7 (999) 123-45-67" required>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Фамилия</label>
                                <input type="text" id="last_name" name="last_name" value="{{Auth::user()->first_name ?? ''}}" placeholder="Иванов" required>
                            </div>

                            <div class="form-group">
                                <label for="first_name">Имя</label>
                                <input type="text" id="first_name" name="first_name" value="{{Auth::user()->last_name ?? ''}}" placeholder="Иван" required>
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Отчество</label>
                                <input type="text" id="middle_name" name="middle_name" value="{{Auth::user()->middle_name ?? ''}}" placeholder="Иванович">
                            </div>
                            <div class="form-group">
                                <label>Фотографии автомобиля (до 10 шт.)</label>
                                <div class="photo-upload-container">
                                    <div class="photo-upload-btn" id="uploadPhotoBtn">
                                        <i>📎</i>
                                        <span>Добавить фотографии</span>
                                    </div>
                                    <input type="file" id="photoInput" class="hidden-file-input" accept="image/jpeg,image/png,image/webp" multiple>
                                    <div class="photo-preview-container" id="photoPreviewContainer"></div>
                                    <div class="error-message" id="photos-error">Необходимо загрузить хотя бы одно фото</div>
                                </div>
                            </div>
                            <div class="buttons-row">
                                <button type="button" class="calculate-btn" id="calculateBtn">Рассчитать</button>
                                <div class="estimate-value" id="estimateValue">
                                    <span id="estimate">Оценка вашего авто : ~</span> ₽
                                </div>
                                <button type="button" class="submit-btn" id="submitBtn">Отправить</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        @auth
            <div class="profile-container">
                <div class="profile-card">
                    <h2 class="profile-title">Ваш профиль</h2>
                    <div class="avatar-edit-container">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Аватар" class="profile-avatar" id="user-avatar">
                        @else
                            <div class="profile-avatar" id="user-avatar" style="background-color: #4a6fa5; display: flex; align-items: center; justify-content: center;">
                                <span style="font-size: 3rem; color: white;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                        @endif
                        <div class="avatar-edit-overlay" id="edit-avatar-btn">
                            <i>✏️</i>
                        </div>
                    </div>


                    <div class="modal" id="avatar-modal">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title">Редактирование аватарки</h3>
                                <span class="close-modal">&times;</span>
                            </div>
                            <div class="dropzone" id="avatar-dropzone">
                                <div class="dz-message">Перетащите сюда фото или кликните для выбора</div>
                            </div>
                            <div class="image-container">
                                <img id="image-to-crop" style="display: none;">
                            </div>
                            <div class="modal-actions">
                                <button class="modal-btn cancel-btn" id="cancel-crop">Отмена</button>
                                <button class="modal-btn save-btn" id="save-crop" style="display: none;">Сохранить</button>
                            </div>
                        </div>
                    </div>


                    <div class="profile-status">
                        <div class="status-indicator status-online"></div>
                        <span>Online</span>
                    </div>

                    <div class="profile-info">
                        <div class="profile-detail">
                            <div class="profile-detail-label">Имя</div>
                            <div class="profile-detail-value">{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</div>
                        </div>

                        <div class="profile-detail">
                            <div class="profile-detail-label">Email</div>
                            <div class="profile-detail-value">{{ Auth::user()->email }}</div>
                        </div>

                        <div class="profile-detail">
                            <div class="profile-detail-label">Телефон</div>
                            <div class="profile-detail-value">{{ Auth::user()->phone ?? 'Не указан' }}</div>
                        </div>

                        @if(Auth::user()->birth_date)
                            <div class="profile-detail">
                                <div class="profile-detail-label">Дата рождения</div>
                                <div class="profile-detail-value">{{ \Carbon\Carbon::parse(Auth::user()->birth_date)->format('d.m.Y') }}</div>
                            </div>
                        @endif

                        <div class="profile-detail">
                            <div class="profile-detail-label">Роль</div>
                            <div class="profile-detail-value">{{ Auth::user()->role === 'seller' ? 'Продавец' : 'Покупатель' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        @endauth
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script src="https://unpkg.com/dropzone@5/dist/min/dropzone.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

<script>
    Dropzone.autoDiscover = false;
    // Конфигурация Toastr
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
    // Массив для хранения загруженных файлов
    let uploadedPhotos = [];
    // Инициализация Select2
    $(document).ready(function() {

            // Остальная инициализация (Select2 и т.д.)

            // Обработчик клика по кнопке загрузки фото
            document.getElementById('uploadPhotoBtn').addEventListener('click', function() {
                document.getElementById('photoInput').click();
            });

            // Обработчик изменения input файла
            document.getElementById('photoInput').addEventListener('change', function(e) {
                const files = e.target.files;

                // Проверяем количество файлов
                if (files.length + uploadedPhotos.length > 10) {
                    toastr.warning('Максимальное количество фото - 10', 'Внимание');
                    return;
                }

                // Обрабатываем каждый файл
                for (let i = 0; i < files.length; i++) {
                    const file = files[i];

                    // Проверяем тип файла
                    if (!file.type.match('image.*')) {
                        toastr.error('Пожалуйста, загружайте только изображения (JPEG, PNG, WebP)', 'Ошибка');
                        continue;
                    }

                    // Проверяем размер файла (до 5MB)
                    if (file.size > 5 * 1024 * 1024) {
                        toastr.error('Файл "' + file.name + '" слишком большой. Максимальный размер - 5MB', 'Ошибка');
                        continue;
                    }

                    // Добавляем файл в массив
                    uploadedPhotos.push(file);

                    // Создаем превью
                    createPhotoPreview(file);
                }

                // Сбрасываем input, чтобы можно было загружать те же файлы снова
                e.target.value = '';

                // Скрываем ошибку, если она была показана
                document.getElementById('photos-error').style.display = 'none';
            });

            // Функция создания превью фотографии
            function createPhotoPreview(file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const previewContainer = document.getElementById('photoPreviewContainer');
                    const previewDiv = document.createElement('div');
                    previewDiv.className = 'photo-preview';

                    const img = document.createElement('img');
                    img.src = e.target.result;

                    const removeBtn = document.createElement('div');
                    removeBtn.className = 'remove-photo';
                    removeBtn.innerHTML = '×';
                    removeBtn.addEventListener('click', function() {
                        // Удаляем файл из массива
                        uploadedPhotos = uploadedPhotos.filter(f => f !== file);
                        // Удаляем превью
                        previewContainer.removeChild(previewDiv);
                    });

                    previewDiv.appendChild(img);
                    previewDiv.appendChild(removeBtn);
                    previewContainer.appendChild(previewDiv);
                };

                reader.readAsDataURL(file);
            }

        // Динамическое обновление моделей при выборе марки
        $('#brand').on('change', function() {
            const brand = this.value;
            const modelSelect = $('#model');

            modelSelect.empty().append('<option value="">Загрузка...</option>');

            if (brand) {
                const brandsData = <?php echo json_encode($brands, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

                modelSelect.empty().append('<option value="">Выберите модель</option>');

                if (brandsData[brand]) {
                    brandsData[brand].forEach(function(model) {
                        modelSelect.append(new Option(model, model.toLowerCase().replace(/\s+/g, '-')));
                    });
                }
            } else {
                modelSelect.empty().append('<option value="">Сначала выберите марку</option>');
            }

            modelSelect.trigger('change');
        });
    });
    let est = 0;
    // Обработчик кнопки "Рассчитать стоимость"
    document.getElementById('calculateBtn').addEventListener('click', function() {
        const form = document.getElementById('carForm');
        // Проверяем валидность формы
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Проверяем дополнительные валидации
        let isValid = true;

        // Проверка номера
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
            data: pageData,
            success: function(data) {
                calculateBtn.disabled = false;
                calculateBtn.textContent = 'Рассчитать стоимость';

                if(data && data.estimate) {
                    est = data.estimate;
                    document.getElementById('estimateValue').style.display = 'block';
                    document.getElementById('estimate').textContent = data.estimate;
                    document.getElementById('submitBtn').style.display = 'block';
                    document.getElementById('calculateBtn').style.display = 'none';
                } else {
                    toastr.error('Не удалось рассчитать стоимость. Пожалуйста, проверьте введенные данные.', 'Ошибка');
                }
            },
            error: function() {
                calculateBtn.disabled = false;
                calculateBtn.textContent = 'Рассчитать стоимость';
                toastr.error('Произошла ошибка при расчете стоимости. Пожалуйста, попробуйте позже.', 'Ошибка');
            }
        });
    });

    // Обработчик кнопки "Отправить заявку"
    document.getElementById('submitBtn').addEventListener('click', function() {
        const form = document.getElementById('carForm');

        // Проверяем, есть ли загруженные фото
        if (uploadedPhotos.length === 0) {
            document.getElementById('photos-error').style.display = 'block';
            return;
        } else {
            document.getElementById('photos-error').style.display = 'none';
        }

        // Проверяем валидность формы
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Создаем FormData и добавляем данные формы
        let formData = new FormData();

        // Добавляем данные формы
        $('#carForm').serializeArray().forEach(item => {
            formData.append(item.name, item.value);
        });

        // Добавляем оценку
        formData.append('amount', est);

        // Добавляем фотографии
        uploadedPhotos.forEach((file, index) => {
            formData.append(`photos[${index}]`, file);
        });

        // Показываем индикатор загрузки
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Отправка...';

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'POST',
            url: '{{ route("addRequest") }}',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Отправить';

                if(data.success) {
                    window.location.reload();
                    toastr.success('Заявка успешно отправлена!', 'Успех');
                } else {
                    toastr.error('Произошла ошибка при отправке заявки: ' + (xhr.responseJSON?.message || 'Неизвестная ошибка'), 'Ошибка');
                }
            },
            error: function(xhr) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Отправить';
                alert('Произошла ошибка при отправке заявки: ' + (xhr.responseJSON?.message || 'Неизвестная ошибка'));
            }
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
    document.getElementById('phone').addEventListener('input', function(e) {
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




    $(document).ready(function() {
        let cropper;
        let avatarModal = document.getElementById('avatar-modal');
        let editAvatarBtn = document.getElementById('edit-avatar-btn');
        let closeModalBtn = document.querySelector('.close-modal');
        let cancelCropBtn = document.getElementById('cancel-crop');
        let saveCropBtn = document.getElementById('save-crop');
        let imageToCrop = document.getElementById('image-to-crop');
        let userAvatar = document.getElementById('user-avatar');
        let myDropzone = null;

        // Отключаем авто-обнаружение Dropzone
        Dropzone.autoDiscover = false;

        // Удаляем предыдущие экземпляры Dropzone, если они есть
        if (Dropzone.instances.length > 0) {
            Dropzone.instances.forEach(function(instance) {
                instance.destroy();
            });
        }

        // Инициализация Dropzone только если элемент существует и еще не инициализирован
        let dropzoneElement = document.getElementById("avatar-dropzone");
        if (dropzoneElement && !dropzoneElement.dropzone) {
            myDropzone = new Dropzone(dropzoneElement, {
                url: "#", // Временный URL, будет переопределен при сохранении
                paramName: "avatar",
                maxFiles: 1,
                maxFilesize: 5, // MB
                acceptedFiles: "image/jpeg,image/png,image/gif",
                addRemoveLinks: false,
                autoProcessQueue: false,
                dictDefaultMessage: "Перетащите сюда фото или кликните для выбора",
                init: function() {
                    this.on("addedfile", function(file) {
                        // Удаляем предыдущий файл, если есть
                        if (this.files.length > 1) {
                            this.removeFile(this.files[0]);
                        }

                        // Показываем изображение для обрезки
                        let reader = new FileReader();
                        reader.onload = function(e) {
                            imageToCrop.src = e.target.result;
                            imageToCrop.style.display = 'block';

                            // Инициализируем Cropper.js
                            if (cropper) {
                                cropper.destroy();
                            }

                            cropper = new Cropper(imageToCrop, {
                                aspectRatio: 1,
                                viewMode: 1,
                                autoCropArea: 0.8,
                                responsive: true,
                                guides: false
                            });

                            saveCropBtn.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        }

        // Открытие модального окна
        editAvatarBtn.addEventListener('click', function() {
            avatarModal.style.display = 'block';
        });

        // Закрытие модального окна
        closeModalBtn.addEventListener('click', function() {
            avatarModal.style.display = 'none';
            resetModal();
        });

        cancelCropBtn.addEventListener('click', function() {
            avatarModal.style.display = 'none';
            resetModal();
        });

        // Сохранение обрезанного изображения
        saveCropBtn.addEventListener('click', function() {
            if (cropper) {
                // Получаем обрезанное изображение
                cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                    minWidth: 120,
                    minHeight: 120,
                    maxWidth: 600,
                    maxHeight: 600,
                    fillColor: '#fff',
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high'
                }).toBlob(function(blob) {
                    // Создаем FormData и отправляем на сервер
                    let formData = new FormData();
                    formData.append('avatar', blob, 'avatar.png');
                    formData.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: "{{ route('save.avatar') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.success) {
                                // Обновляем аватарку
                                if (userAvatar.tagName === 'IMG') {
                                    userAvatar.src = response.avatar_url + '?' + new Date().getTime();
                                } else {
                                    // Если был дефолтный аватар, заменяем на img
                                    let newAvatar = document.createElement('img');
                                    newAvatar.src = response.avatar_url;
                                    newAvatar.className = 'profile-avatar';
                                    newAvatar.id = 'user-avatar';
                                    userAvatar.parentNode.replaceChild(newAvatar, userAvatar);
                                    userAvatar = newAvatar;
                                }

                                avatarModal.style.display = 'none';
                                resetModal();
                            }
                        },
                        error: function(xhr) {
                            atoastr.error('Ошибка при сохранении аватарки: ' + (xhr.responseJSON?.message || 'Неизвестная ошибка'), 'Ошибка');;
                        }
                    });
                });
            }
        });

        // Закрытие модального окна при клике вне его
        window.addEventListener('click', function(event) {
            if (event.target === avatarModal) {
                avatarModal.style.display = 'none';
                resetModal();
            }
        });

        // Сброс состояния модального окна
        function resetModal() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }

            // Добавляем проверку на существование myDropzone
            if (typeof myDropzone !== 'undefined' && myDropzone.files && myDropzone.files.length > 0) {
                myDropzone.removeFile(myDropzone.files[0]);
            }

            imageToCrop.src = '';
            imageToCrop.style.display = 'none';
            saveCropBtn.style.display = 'none';
        }
    });
</script>
<!-- Toastr JS -->
</body>
</html>
