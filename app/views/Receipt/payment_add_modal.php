<div class="has-feedback">
    <label for="name">Наименование контрагента</label>
    <input type="text" name="name" class="form-control" id="name" placeholder="Наименование КА" value="<?=isset($_SESSION['payment']['partner']) ? h($_SESSION['payment']['partner']) : '';?>" disabled>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="date">Дата завки на оплату</label>
        <input type="date" name="date" class="form-control" id="date" placeholder="01.01.2021" value="<?=isset($_SESSION['payment']['date']) ? h($_SESSION['payment']['date']) : '';?>" required>
    </div>
    <div class="has-feedback col-6">
        <label for="number">Номер заявки</label>
        <input type="text" name="number" class="form-control" id="number" placeholder="Номер" value="<?=isset($_SESSION['payment']['number']) ? h($_SESSION['payment']['number']) : '';?>" required>
    </div>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="sum">Сумма оплаты</label>
        <?php
        $sum = '';
        if (is_array($_SESSION['payment']['sum'])) {
            foreach ($_SESSION['payment']['sum'] as $k => $value) {
                if (is_array($_SESSION['payment']['receipt_current'])) {
                    if (in_array($value['number'], $_SESSION['payment']['receipt_current'])) {
                        $sum .= $value['summa'] . ';';
                    }
                } else {
                    if ($value['number'] == $_SESSION['payment']['receipt_current']) {
                        $sum .= $value['summa'] . ';';
                    }
                }
            }
            $sum = rtrim($sum, ';');
        } else $sum = $_SESSION['payment']['sum'];
        ?>
        <input type="text" name="sum" class="form-control" id="sum"  placeholder="" step="0.01" value="<?=isset($sum) ? $sum : '';?>" required>
    </div>
    <div class="has-feedback col-6">
        <label for="vat">НДС</label>
        <select class="form-control" name="vat" id="vat">
            <option value="1.20" <?php if ($_SESSION['payment']['vat'] == '1.20') { echo ' selected';} ?>>20%</option>
            <option value="1.00" <?php if ($_SESSION['payment']['vat'] == '1.00') { echo ' selected';} ?>>Без НДС</option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="receipt_select">Номера приходов</label><br>
        <select name="receipt[]" id="receipt_select" data-placeholder="Выберите приход..." class="number_receipt_select" multiple>
            <?php foreach ($_SESSION['payment']['receipt'] as $k => $value) : ?>
                <option value="<?= $value['number'];?>" data-sum="<?= $value['summa'];?>"
                    <?php
                    if (is_array($_SESSION['payment']['receipt_current'])) {
                        if (in_array($value['number'], $_SESSION['payment']['receipt_current'])) echo " selected";
                    } else {
                        if ($value['number'] == $_SESSION['payment']['receipt_current']) echo " selected";
                    }
                    ?>
                ><?= $value['number'];?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="has-feedback col-6">
        <label for="date_pay">Дата оплаты</label>
        <input type="date" name="date_pay" class="form-control" id="date_pay" placeholder="" value="<?=isset($_SESSION['payment']['date_pay']) ? h($_SESSION['payment']['date_pay']) : '';?>" required>
    </div>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="num_er">Номер ЕР</label><br>
        <select name="num_er[]" id="num_er" data-placeholder="Выберите ЕР..." class="num_er_select" multiple>
            <?php foreach ($_SESSION['payment']['num_er'] as $k => $v) : ?>
                <optgroup label="<?= $v['budget'];?>">
                    <option value="<?= $v['number'];?>"
                    <?php
                        if (is_array($_SESSION['payment']['num_er_current'])) {
                            if (in_array($v['number'], $_SESSION['payment']['num_er_current'])) {
                                echo " selected";
                            }
                        }
                    ?>
                    ><?= $v['number'];?></option>
                </optgroup>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="has-feedback col-6">
        <label for="sum_er">Сумма ЕР</label>
        <input type="text" name="sum_er" class="form-control" id="sum_er" placeholder="" value="<?=isset($_SESSION['payment']['sum_er']) ? h($_SESSION['payment']['sum_er']) : '';?>" required>
    </div>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="num_bo">Номер БО</label>
        <input type="text" name="num_bo" class="form-control" id="num_bo" placeholder="Номер документа" value="<?=isset($_SESSION['payment']['num_bo']) ? h($_SESSION['payment']['num_bo']) : '';?>" required>
    </div>
    <div class="has-feedback col-6">
        <label for="sum_bo">Сумма БО</label>
        <input type="text" name="sum_bo" class="form-control" id="sum_bo" placeholder="" value="<?=isset($_SESSION['payment']['sum_bo']) ? h($_SESSION['payment']['sum_bo']) : '';?>" required>
    </div>
</div>
<input type="hidden" name="partner" value="<?=isset($_SESSION['payment']['partner']) ? h($_SESSION['payment']['partner']) : '';?>">
<input type="hidden" name="id" value="<?=isset($_SESSION['payment']['id']) ? h($_SESSION['payment']['id']) : '';?>">
<script type="text/javascript" src="chosen/chosen.jquery.min.js"></script>
<script type="text/javascript">
    $(function(){
        $(".number_receipt_select").chosen({
            width: "100%"
        });
    })
    $(function(){
        $(".num_er_select").chosen({
            width: "100%"
         });
    })
</script>
