<main class="content">
    <div class="container">
        <h1 class="mt-1">Добавление/изменение оплат</h1>
        <?php if ($partner) : ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=PATH;?>">Главная</a></li>
                <li class="breadcrumb-item"><a href="<?=PATH;?>/partner">Список контрагентов</a></li>
                <li class="breadcrumb-item"><a href="<?=PATH;?>/partner/<?=$partner['inn']; ?>"><?=$partner['name']; ?></a></li>
                <li class="breadcrumb-item active" aria-current="page">Ввод оплат</li>
            </ol>
        </nav>
        <div class="row d-flex justify-content-center">
            <div class="col-9">
                <form method="post" action="receipt/pay-receipt" id="partner_payment" class="was-validated" novalidate>
                    <div class="has-feedback">
                        <label for="name">Наименование контрагента</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Наименование КА" value="<?=isset($partner['name']) ? $partner['name'] : 'Нет данных';?>" disabled>
                    </div>
                    <div class="form-row">
                        <div class="has-feedback col-6">
                            <label for="date">Дата заявки на оплату</label>
                            <input type="date" name="date" class="form-control" id="date" placeholder="01.01.2021" value="<?=isset($payments['date']) ? $payments['date'] : '';?>" required>
                            <div class="invalid-feedback">
                                Введите дату формирования заявки на оплату
                            </div>
                        </div>
                        <div class="has-feedback col-6">
                            <label for="number">Номер заявки</label>
                            <input type="text" name="number" class="form-control" id="number" placeholder="Номер" value="<?=isset($payments['number']) ? $payments['number'] : '';?>" required>
                            <div class="invalid-feedback">
                                Введите номер сформированной заявки на оплату
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="has-feedback col-6">
                            <label for="sum_select" id="sum">Сумма оплаты</label>
                            <select name="sum[]" id="sum_select" data-placeholder="Выберите сумму..." class="sum_receipt_select" multiple>
                                <?php foreach ($receipt_no_pay as $k => $value) : ?>
                                    <option value="<?= $value['summa'];?>" data-number="<?= $value['number'];?>"
                                        <?php if (in_array(array('number' => $value['number'], 'summa' => $value['summa']), $receipt_select)) echo " selected";?>
                                    ><?= $value['summa'];?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Выберите приход для оплаты
                            </div>
                        </div>
                        <div class="has-feedback col-6">
                            <label for="vat">НДС</label>
                            <select class="form-control" name="vat" id="vat">
                                <option value="1.20" <?php if ($vat == '1.20') { echo ' selected';} ?>>20%</option>
                                <option value="1.00" <?php if ($vat == '1.00') { echo ' selected';} ?>>Без НДС</option>
                            </select>
                            <div class="invalid-feedback">
                                Выберите ставку НДС
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="has-feedback col-6">
                            <label for="receipt_select">Номера приходов</label><br>
                            <select name="receipt[]" id="receipt_select" data-placeholder="Выберите приход..." class="number_receipt_select" multiple>
                                <?php foreach ($receipt_no_pay as $k => $value) : ?>
                                    <option value="<?= $value['number'];?>" data-sum="<?= $value['summa'];?>"
                                        <?php if (in_array(array('number' => $value['number'], 'summa' => $value['summa']), $receipt_select)) echo " selected";?>
                                    ><?= $value['number'];?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Выберите приход для оплаты
                            </div>
                        </div>
                        <div class="has-feedback col-6">
                            <label for="date_pay">Дата оплаты</label>
                            <input type="date" name="date_pay" class="form-control" id="date_pay" placeholder="" value="<?=isset($payments['date_pay']) ? $payments['date_pay'] : '';?>" required>
                            <div class="invalid-feedback">
                                Введите дату предпологаемой оплаты
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="has-feedback col-6">
                            <label for="num_er">Номер ЕР</label><br>
                            <select name="num_er[]" id="num_er" data-placeholder="Выберите ЕР..." class="num_er_select" multiple>
                                <?php foreach ($ers as $k => $v) : ?>
                                    <optgroup label="<?= $v['budget'];?>">
                                        <option value="<?= $v['number'];?>"
                                            <?php
                                            if (isset($ers_sel)) {
                                                foreach ($ers_sel as $er) {
                                                    if ($er['number'] == $v['number']) {
                                                        echo " selected";
                                                    }
                                                }
                                            }
                                            ?>
                                        ><?= $v['number'];?></option>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">
                                Выберите ЕР которые служат для оплаты
                            </div>
                        </div>
                        <div class="has-feedback col-6">
                            <label for="sum_er">Сумма ЕР</label>
                            <?php
                            $sum_str = '';
                            if (isset($ers_sel)) {
                                foreach ($ers_sel as $er) {
                                    $sum_str .= $er['summa'] . ';';
                                }
                                $sum_str = rtrim($sum_str, ';');
                            }
                            ?>
                            <input type="text" name="sum_er" class="form-control" id="sum_er" placeholder="" value="<?=$sum_str;?>" required>
                            <div class="invalid-feedback">
                                Введите суммы для оплаты
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="has-feedback col-6">
                            <label for="num_bo">Номер БО</label>
                            <input type="text" name="num_bo" class="form-control" id="num_bo" placeholder="Номер документа" value="<?=isset($payments['num_bo']) ? $payments['num_bo'] : '';?>" required>
                            <div class="invalid-feedback">
                                Введите номера БО используемых для оплаты
                            </div>
                        </div>
                        <div class="has-feedback col-6">
                            <label for="sum_bo">Сумма БО</label>
                            <input type="text" name="sum_bo" class="form-control" id="sum_bo" placeholder="" value="<?=isset($payments['sum_bo']) ? $payments['sum_bo'] : '';?>" required>
                            <div class="invalid-feedback">
                                Введите суммы БО используемых для оплаты
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="partner" value="<?=isset($partner['name']) ? $partner['name'] : '';?>">
                    <input type="hidden" name="id" value="<?=isset($payments['id']) ? $payments['id'] : '';?>">
                    <input type="hidden" name="inn" value="<?=isset($partner['inn']) ? $partner['inn'] : '';?>">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary mt-3">Создать оплату</button>
                    </div>

                </form>
            </div>
        </div>
        <?php else : ?>
        <h3>Отсутствуют данные для обработки</h3>
        <?php endif; ?>
    </div>
</main>
<script>
    $(function () {
        $(".number_receipt_select").chosen({
            width: "100%"
        });
        $(".num_er_select").chosen({
            width: "100%"
        });
        $(".sum_receipt_select").chosen({
            width: "100%"
        });
        $("#sum_select").change(function() {
            const ids = $(this).val();
            let sum = 0;
            for(let i = 0; i < ids.length; i++) {
                let $select = $(this);
                console.log($select.children().eq(i).data('number'));
                sum += parseFloat(ids[i]);
            }
            $('#sum_er').val(sum.toFixed(2));
            $('#sum_bo').val(sum.toFixed(2));
        });
    });
</script>
