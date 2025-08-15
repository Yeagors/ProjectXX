import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

$(document).ready(function() {
    // Инициализация Select2
    $('.select2').select2({
        width: '100%',
        theme: 'dark'
    });

    // Динамическое обновление моделей
    $('#brand').on('change', function() {
        const brand = this.value;
        const modelSelect = $('#model');

        modelSelect.empty().append('<option value="">Загрузка...</option>');

        if (brand) {
            modelSelect.empty().append('<option value="">Выберите модель</option>');

            if (window.AppData.brands[brand]) {
                window.AppData.brands[brand].forEach(function(model) {
                    modelSelect.append(new Option(model, model.toLowerCase().replace(/\s+/g, '-')));
                });
            }
        } else {
            modelSelect.empty().append('<option value="">Сначала выберите марку</option>');
        }
    });

    // Установка максимального года
    $('#year').attr('max', new Date().getFullYear());

    // Обработчик кнопки "Рассчитать стоимость"
    $('#calculateBtn').on('click', function() {
        if (!$('#carForm')[0].checkValidity()) {
            $('#carForm')[0].reportValidity();
            return;
        }

        const btn = $(this);
        btn.prop('disabled', true).text('Рассчитываем...');

        $.ajax({
            headers: {'X-CSRF-TOKEN': window.AppData.csrfToken},
            type: 'POST',
            url: window.AppData.calculatePriceUrl,
            data: {
                brand: $('#brand').val(),
                model: $('#model').val(),
                kpp: $('#kpp').val(),
                year: $('#year').val(),
                license_plate: $('#license_plate').val()
            },
            success: function(data) {
                btn.prop('disabled', false).text('Рассчитать');
                if(data?.estimate) {
                    $('#estimateValue').show();
                    $('#estimate').text(data.estimate);
                    $('#submitBtn').show();
                    $('#calculateBtn').hide();
                }
            },
            error: function() {
                btn.prop('disabled', false).text('Рассчитать');
                alert('Ошибка при расчете стоимости');
            }
        });
    });

    // Обработчик кнопки "Отправить заявку"
    $('#submitBtn').on('click', function() {
        $.ajax({
            headers: {'X-CSRF-TOKEN': window.AppData.csrfToken},
            type: 'POST',
            url: window.AppData.addRequestUrl,
            data: $('#carForm').serialize(),
            success: function() {
                alert('Заявка успешно отправлена!');
                window.location.reload();
            },
            error: function() {
                alert('Ошибка при отправке заявки');
            }
        });
    });

    // Изначально скрываем кнопку отправки
    $('#submitBtn').hide();
});
