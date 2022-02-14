<main role="main" class="flex-shrink-0">
  <div class="container">
    <h1 class="mt-1">Приходы требующие оплаты</h1>
    <?php if($rec): ?>
    <table class="table table-striped table-sm">
      <thead>
        <tr class="table-active text-center">
          <th scope="col" class="h-100 align-middle">Имя КА</th>
          <th scope="col" class="h-100 align-middle">Документ</th>
          <th scope="col" class="h-100 align-middle">Сумма</th>
          <th scope="col" class="h-100 align-middle">Дата оплаты</th>
          <th scope="col" class="h-100 align-middle" colspan="2">Статус</th>
        </tr>
      </thead> 
      <tbody>
        <?php foreach ($rec as $item): ?>
        <?php
            $status = '';
            $color= '';
            if (!$item['num_pay']) {
                $status = 'Приход не обработан';
                $color = ' table-danger';
            } elseif (!$item['date_pay']) {
                $status = 'Подано на оплату';
                $color = ' table-warning';
            } elseif ($item['date_pay'] = date('Y-m-d')) {
                $status = 'Оплачено';
                $color = ' table-success';
            }
            if ($item['delay']) {
                $date_elements = explode('-', $item['date']);
                $date = new DateTime($item['date']);
                $delay = (int)$item['delay'];
                date_add($date, date_interval_create_from_date_string("$delay days"));
                $pay = date_format($date, 'Y-m-d');
            } else {
                $pay = 'Нет данных';
            }
        ?>
        <tr>
          <th class="h-100 align-middle" scope="row"><a href="partner/<?= $item['inn'];?>"><?= $item['partner'];?></a></th>
          <td class="h-100 align-middle">Поступление товаров и услуг <?= $item['number'];?> от <?= $item['date'];?></td>
          <td class="text-center h-100 align-middle"><?= number_format($item['sum'], 2, ',', '&nbsp;');?>&nbsp;₽</td>
          <td class="text-center h-100 align-middle<?= $color;?>">
              <?= $pay;?>
          </td>
          <td class="text-center h-100 align-middle"><?= $status;?></td>
          <td class="text-center h-100 align-middle">
            <button type="button" class="btn btn-outline-success btn-sm w-100" data-toggle="modal" data-target="#payModal">Оплата</button>
            <button type="button" class="btn btn-outline-warning btn-sm w-100" data-toggle="modal" data-target="#editModal">Править</button>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
    
        <nav aria-label="Page navigation example">
          <ul class="pagination justify-content-center">
            <?php if ($pagination->countPages > 1) : ?>
              <?=$pagination;?>
            <?php endif; ?>
          </ul>
        </nav>
  </div>
</main>

<div id="payModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Подаем на оплату</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Здесь будет форма оплаты</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary">Оплатить</button>
            </div>
        </div>
    </div>
</div>

<div id="editModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Редактирование статуса</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Здесь будет поля для редактирования</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
                <button type="button" class="btn btn-primary">Записать</button>
            </div>
        </div>
    </div>
</div>