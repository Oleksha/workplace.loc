$(document).ready(function() {
    $('#bo_view').dataTable( {
        "ordering": false,
        "language": {
            "url": "/DataTables/DataTables-1.11.3/js/ru.json"
        }
    });
    $('#example').dataTable( {
        "aLengthMenu": [[7, 15, 25, -1], [7, 15, 25, "All"]],
        "language": {
            "url": "/DataTables/DataTables-1.11.3/js/ru.json"
        },
        "aoColumns": [
            null,
            {"bSearchable": false },
            {"sClass": "text-center",
                "bSearchable": false },
            {"sClass": "text-center",
                "bSearchable": false },
            {"sClass": "text-center",
                "bSearchable": false },
            {"sClass": "text-center",
                "bSearchable": false },
            {"sClass": "text-center",
                "bSearchable": false },

        ]
    } );
    $('#main_index').dataTable( {
        "order": [[ 3, "asc" ]],        
        "createdRow": function ( row, data, index ) {
                if ( data[4] == "Подано на оплату" ) {
                    $('td', row).eq(3).addClass('table-warning');
                } 
                if ( data[4] == "Приход не обработан" ) {
                    $('td', row).eq(3).addClass('table-danger');
                }
                if ( data[4] == "Оплачено" ) {
                    $('td', row).eq(3).addClass('table-success');
                }
             },    
        "aLengthMenu": [[9, 15, 25, -1], [9, 15, 25, "All"]],
        "language": {
            "url": "/DataTables/DataTables-1.11.3/js/ru.json"
        },
        "aoColumns": [
            null,
            {"bSearchable": false },
            null,
            {"bSearchable": false },
            {"bSearchable": false },
            {"sClass": "text-center",
                "bSearchable": false }

        ]
    });
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});

/* Фильтр начало */
$('body').on('change', '.filters select', function () {
    let date_y = $('#select_year option:checked'), // получаем все отмеченные чекбоксы
        date_m = $('#select_month option:checked'), // получаем все отмеченные чекбоксы
        data = '';
    date_y.each(function () {
        // пройдем в цикле по всем отмеченным чекбоксам
        data += this.value + '-'; // добавлем в переменную значения года
    });
    date_m.each(function () {
        // пройдем в цикле по всем отмеченным чекбоксам
        data += this.value + '-01'; // добавлем в переменную значения года
    });
    if (data) {
        // если включен какой либо фильтр
        // формируем AJAX запрос
        $.ajax({
            url: location.href,
            data: {filter: data},
            type: 'GET',
            beforeSend: function () {
                // перед отправкой мы должны включить прелоадер
                $('.preloader').fadeIn(100, function() {
                    // обратимся к классу показывающему продукты
                    // и скроем все отображаемые на экране продукты
                    $('#bo_view').hide();
                });
            },
            success: function (res) {
                // постепенно скроем прелоадер и
                $('.preloader').delay(100).fadeOut('show', function () {
                    // в класс показывающий продукты подгружаем
                    // полученный ответ с сервера и показываем его
                    $('#bo_view').html(res).fadeIn();
                    let url = location.search.replace(/filter(.+?)(&|$)/g, ''); //$2
                    let newURL = location.pathname + url + (location.search ? "&" : "?") + "filter=" + data;
                    newURL = newURL.replace('&&', '&');
                    newURL = newURL.replace('?&', '?');
                    history.pushState({}, '', newURL);
                    location.reload();
                });
            },
            error: function (res) {
                alert('Errors');
            }
        });
    }
    //alert(data);
});
/* Фильтр конец */

/* Поиск */
let partners = new Bloodhound ({
    datumTokenizer: Bloodhound.tokenizers.whitespace,
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
        wildcard: '%QUERY',
        url: path + '/search/typeahead?query=%QUERY'
    }
});

partners.initialize();

$("#typeahead").typeahead({
    //hint: false,
    highlight: true
},{
    name: 'partners',
    display: 'name',
    limit: 10,
    source: partners
});

$("#typeahead").bind('typeahead:select', function (ev, suggestion) {
    console.log(suggestion);
    window.location = path + '/search/?s=' + encodeURIComponent(suggestion.name);
})

/* Единоличное решение */
$('body').on('click', '.edit-er-link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let id = $(this).data('id'), // идентификатор ЕР
        partner = $(this).data('partner_id'); // идентификатор КА

    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/er/edit', // всегда указываем от корня
        data: {id: id, partner: partner}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showEr(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

$('body').on('click', '.add-er-link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let partner = $(this).data('id'); // идентификатор КА

    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/er/add', // всегда указываем от корня
        data: {partner: partner}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showAddEr(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

/**
 * Показывает модальное окно с данными ЕР
 * @param er полученные данные
 */
function showEr(er) {
    // выводим содержимое страницы
    $('#editERModal .modal-body').html(er);
    $('#editERModal').modal();
}

function showAddEr(er) {
    // выводим содержимое страницы
    $('#addERModal .modal-body').html(er);
    $('#addERModal').modal();
}

$('body').on('click', '.edit-ka-link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let id = $(this).data('id'); // идентификатор КА

    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/partner/edit', // всегда указываем от корня
        data: {id: id}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showEditKa(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

function showEditKa(ka) {
    // выводим содержимое страницы
    $('#editKAModal .modal-body').html(ka);
    $('#editKAModal').modal();
}

$('body').on('click', '.add-receipt-link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let partner = $(this).data('name'), // наименование КА
        vat = $(this).data('vat'); // идентификаиор КА

    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/receipt/add', // всегда указываем от корня
        data: {vat: vat, partner: partner}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showAddReceipt(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

$('body').on('click', '.edit-receipt-link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let id = $(this).data('id'); // идентификатор прихода

    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/receipt/edit', // всегда указываем от корня
        data: {id: id}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showEditReceipt(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

$('body').on('click', '.pay-receipt-link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let id = $(this).data('id'), // идентификатор прихода
        partner = $(this).data('partner'), // имя контрагента
        vat = $(this).data('vat'); // ставка НДС
    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/receipt/pay', // всегда указываем от корня
        data: {id: id, partner: partner, vat: vat}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showPaymentReceipt(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

function showAddReceipt(receipt) {
    // выводим содержимое страницы
    $('#addReceiptModal .modal-body').html(receipt);
    $('#addReceiptModal').modal();
}
function showEditReceipt(receipt) {
    // выводим содержимое страницы
    $('#editReceiptModal .modal-body').html(receipt);
    $('#editReceiptModal').modal();
}
function showPaymentReceipt(receipt) {
    // выводим содержимое страницы
    $('#payReceiptModal .modal-body').html(receipt);
    $('#payReceiptModal').modal();
}

$('body').on('click', '.payment_pay_link', function (e) {
    e.preventDefault(); // отменяем действие по умолчанию для ссылки или кнопки
    // получаем необходимые нам данные
    let id = $(this).data('id_receipt') // идентификатор прихода
    // отправляем стандартный аякс запрос на сервер
    $.ajax({
        url: '/main/pay', // всегда указываем от корня
        data: {id: id}, // передаем данные
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
            showPaymentOnly(res);
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
});

function showPaymentOnly(receipt) {
    // выводим содержимое страницы
    $('#payModalMain .modal-body').html(receipt);
    $('#payModalMain').modal();
}
