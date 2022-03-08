<main class="content">
    <div class="container">
        <h1 class="mt-1">Карточка контрагента</h1>
        <?php if (!empty($partner)) : ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=PATH;?>">Главная</a></li>
                <li class="breadcrumb-item"><a href="<?=PATH;?>/partner">Список контрагентов</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?=
                    $partner->name; ?></li>
            </ol>
        </nav>
        <h2><?=$partner->name; ?></h2>
        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                            Приходы
                        </button>
                    </h5>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <?php if(!empty($receipt)): ?>
                            <table class="table table-striped table-sm">
                                <thead>
                                <tr class="table-active text-center">
                                    <th scope="col" class="h-100 align-middle">Номер</th>
                                    <th scope="col" class="h-100 align-middle">Дата</th>
                                    <th scope="col" class="h-100 align-middle">Сумма</th>
                                    <th scope="col" class="h-100 align-middle">Документ</th>
                                    <th scope="col" class="h-100 align-middle">Дата док.</th>
                                    <th scope="col" class="h-100 align-middle">Примечание</th>
                                    <th scope="col" class="h-100 align-middle">Оплата</th>
                                    <th scope="col" class="h-100 align-middle">Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($receipt as $item): ?>
                                    <tr>
                                        <th class="text-center h-100 align-middle" scope="row"><a href="#" class="edit-receipt-link" data-id="<?= $item['id'];?>"><?= $item['number'];?></a></th>
                                        <td class="text-center h-100 align-middle"><?= $item['date'];?></td>
                                        <td class="text-center h-100 align-middle"><?= number_format($item['sum'], 2, ',', '&nbsp;');?>&nbsp;₽</td>
                                        <td class="text-center h-100 align-middle"><?= $item['num_doc'];?></td>
                                        <td class="text-center h-100 align-middle"><?= $item['date_doc'];?></td>
                                        <td class="h-100 align-middle"><?= $item['note'];?></td>
                                        <td class="text-center h-100 align-middle"><?= $item['date_pay'];?></td>
                                        <td class="text-center h-100 align-middle">
                                            <a type="button" class="btn btn-outline-info btn-sm pay-receipt-link" data-toggle="tooltip" data-placement="top" title="Оплата" data-id="<?= $item['id'];?>" data-partner="<?= $partner->id;?>" data-vat="<?= $partner->vat;?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z"/>
                                                    <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z"/>
                                                    <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z"/>
                                                    <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z"/>
                                                </svg>
                                            </a>
                                            <a type="button" class="btn btn-outline-info btn-sm"  data-toggle="tooltip" data-placement="top" title="Удалить" href="receipt/del?id=<?= $item['id'];?>" onclick="return window.confirm('OK?');">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z" fill="red"/>
                                                    <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z" fill="red"/>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <h3>Данные о приходах отсутствуют</h3>
                        <?php endif; ?>
                        <div class="d-flex justify-content-center">
                            <a type="button" class="btn btn-outline-info mt-3 add-receipt-link" data-vat="<?= $partner->vat;?>" data-name="<?= $partner->name;?>">Добавить новый приход</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Единоличные решения
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
                    <div class="card-body">
                        <?php if(!empty($ers)): ?>
                            <?php foreach ($ers as $er): ?>
                                <?php $result = false; $result_date = false; $result_sum = false; ?>
                                <?php $date = strtotime('+30 days'); ?>
                                <?php if ($er['data_end'] < date('Y-m-d', $date))
                                {
                                    $result = true;
                                    $result_date = true;
                                }
                                if ($er['summa'] < 30000)
                                {
                                    $result = true;
                                    $result_sum = true;
                                }
                                ?>
                                <div class="row align-items-center">
                                    <div class="col-12">
                                        <div class="
                                            <?php
                                                if ($result) {
                                                    echo 'alert alert-warning';
                                                } else {
                                                    echo 'alert alert-success';
                                                }
                                            ?>" role="alert">
                                            <?= $er['name_budget_item'];?> - <b><i><?= $er['number'];?></i></b> - Осталось: <b><?= number_format($er['summa'] - $er['cost'], 2, ',', '&nbsp;');?>&nbsp;₽</b>
                                        </div>
                                        <div class="row d-flex align-items-center mb-3">
                                            <div class="col-10">
                                                <strong>Период действия: </strong>
                                                <?= $er['data_start'];?>&nbsp;/&nbsp;
                                                <span <?php if ($result_date) echo 'class="alert-warning"' ?>>
                                                    <?= $er['data_end'];?>
                                                </span>;
                                                <strong>Cумма: </strong>
                                                <span>
                                                    <?= number_format($er['summa'], 2, ',', '&nbsp;');?>&nbsp;₽
                                                </span>
                                            </div>
                                            <div class="col-2 text-right">
                                                <a type="button" class="btn btn-outline-info edit-er-link" data-id="<?= $er['id'];?>" data-toggle="tooltip" data-placement="top" title="Изменить" data-partner_id="<?= $partner->id;?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-outline-danger del-er-link"  data-toggle="tooltip" data-placement="top" title="Удалить" href="er/del?id=<?= $er['id'];?>" onclick="return window.confirm('OK?');">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd" d="M13.854 2.146a.5.5 0 0 1 0 .708l-11 11a.5.5 0 0 1-.708-.708l11-11a.5.5 0 0 1 .708 0Z"/>
                                                        <path fill-rule="evenodd" d="M2.146 2.146a.5.5 0 0 0 0 .708l11 11a.5.5 0 0 0 .708-.708l-11-11a.5.5 0 0 0-.708 0Z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="alert alert-danger" role="alert">Нет действующих единоличных решений</div>
                        <?php endif; ?>
                            <div class="d-flex justify-content-center">
                                <a type="button" href="er/add" class="btn btn-outline-info mt-3 add-er-link" data-id="<?= $partner->id;?>">Добавить новое ЕР</a>
                            </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                            Юридическая информация
                        </button>
                    </h5>
                </div>

                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        <h5 class="card-title">Юридический адрес</h5>
                        <p class="card-text">
                            <?php
                            if (!$partner->address) {
                                echo 'Нет данных';
                            } else {
                                echo $partner->address;
                            }
                            ?>
                        </p>
                        <h5 class="card-title">ИНН / КПП</h5>
                        <p class="card-text">
                            <?php
                            if (!$partner->inn) {
                                echo 'Нет данных';
                            } else {
                                echo $partner->inn;
                            }
                            ?>&nbsp;/&nbsp;
                            <?php
                            if (!$partner->kpp) {
                                echo 'Нет данных';
                            } else {
                                echo $partner->kpp;
                            }
                            ?>
                        </p>
                        <h5 class="card-title">e-mail</h5>
                        <p class="card-text">
                            <?php
                            if (!$partner->email) {
                                echo 'Нет данных';
                            } else {
                                echo '<a href="mailto:'.$partner->email.'">'.$partner->email.'</a>';
                            }
                            ?>
                        </p>
                        <h5 class="card-title">р/с / Банк / БИК</h5>
                        <p class="card-text">
                            <?php
                            if (!$partner->kpp) {
                                echo 'Нет данных';
                            } else {
                                echo $partner->account;
                            }
                            ?>&nbsp;/&nbsp;
                            <?php
                            if (!$partner->kpp) {
                                echo 'Нет данных';
                            } else {
                                echo $partner->bank;
                            }
                            ?>&nbsp;/&nbsp;
                            <?php
                            if (!$partner->kpp) {
                                echo 'Нет данных';
                            } else {
                                echo $partner->bic;
                            }
                            ?>
                        </p>
                        <a type="button" class="btn btn-outline-info edit-ka-link" data-id="<?= $partner->id;?>">Редактировать</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</main>

<div id="editKAModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование данных юридического лица</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="partner/edit-ka" id="er_edit" role="form" class="was-validated">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Записать</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="editERModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование данных ЕР</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="er/edit-er" id="er_edit" role="form" class="was-validated">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Записать</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="addERModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление нового ЕР</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="er/add-er" id="er_add" role="form" class="was-validated">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="submit" class="btn btn-primary">Сохранить</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div id="addReceiptModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление нового прихода</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="receipt/add-receipt" id="receipt_add" role="form" class="was-validated">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editReceiptModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование данных прихода</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="receipt/edit-receipt" id="receipt_add" role="form" class="was-validated">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="payReceiptModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Внесение/Изменение данных об оплате </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="receipt/pay-receipt" id="receipt_pay" role="form" class="was-validated">
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>

