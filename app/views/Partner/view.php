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
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Юридическая информация
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
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
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Единоличные решения
                        </button>
                    </h5>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
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
                                            <?= $er['name_budget_item'];?> - <b><i><?= $er['number'];?></i></b>
                                        </div>
                                        <div class="row d-flex align-items-center mb-3">
                                            <div class="col-10">
                                                <strong>Период действия: </strong>
                                                <?= $er['data_start'];?>&nbsp;/&nbsp;
                                                <span <?php if ($result_date) echo 'class="alert-warning"' ?>>
                                                    <?= $er['data_end'];?>
                                                </span>;
                                                <strong>Оставшаяся сумма: </strong>
                                                <span <?php if ($result_sum) echo 'class="alert-warning"' ?>>
                                                    <?= number_format($er['summa'], 2, ',', '&nbsp;');?>&nbsp;₽
                                                </span>
                                            </div>
                                            <div class="col-2">
                                                <a type="button" class="btn btn-outline-info edit-er-link" data-id="<?= $er['id'];?>" data-toggle="tooltip" data-placement="top" title="Изменить" data-partner_id="<?= $partner->id;?>">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"></path>
                                                    </svg>
                                                </a>
                                                <a type="button" class="btn btn-outline-info del-er-link"  data-toggle="tooltip" data-placement="top" title="Удалить" href="er/del?id=<?= $er['id'];?>">
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
                <div class="card-header" id="headingThree">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                            Приходы
                        </button>
                    </h5>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordion">
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
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($receipt as $item): ?>
                                <tr>
                                    <th class="text-center h-100 align-middle" scope="row"><a href="receipt/edit" class="edit-receipt-link" data-id="<?= $item['id'];?>"><?= $item['number'];?></a></th>
                                    <td class="text-center h-100 align-middle"><?= $item['date'];?></td>
                                    <td class="text-center h-100 align-middle"><?= number_format($item['sum'], 2, ',', '&nbsp;');?>&nbsp;₽</td>
                                    <td class="text-center h-100 align-middle"><?= $item['num_doc'];?></td>
                                    <td class="text-center h-100 align-middle"><?= $item['date_doc'];?></td>
                                    <td class="h-100 align-middle"><?= $item['note'];?></td>
                                    <td class="text-center h-100 align-middle"><?= $item['date_pay'];?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php else : ?>
                        <h3>Данные о приходах отсутствуют</h3>
                        <?php endif; ?>
                        <div class="d-flex justify-content-center">
                            <a type="button" class="btn btn-outline-info mt-3 add-receipt-link" data-name="<?= $partner->name;?>">Добавить новый приход</a>
                        </div>
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
    <div class="modal-dialog " role="document">
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
    <div class="modal-dialog " role="document">
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
}