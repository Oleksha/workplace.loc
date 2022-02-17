$(document).ready(function() {
    $('#example').dataTable( {
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
});
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
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
 * Показывает нашу корзину
 * @param cart полученные данные
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

function clearEr() {
    $.ajax({
        url: '/er/clear', // всегда указываем от корня
        type: 'GET', // тип передаваемого запроса
        success: function (res) {
            // если данные получены
        },
        error: function () {
            // если данных нет или запрос не дошел
            alert('Ошибка получения данных с сервера! Попробуйте позже.');
        }
    });
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

