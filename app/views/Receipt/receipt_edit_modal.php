<input type="hidden" name="id" value="<?=isset($_SESSION['receipt']['id']) ? h($_SESSION['receipt']['id']) : '';?>">
<div class="has-feedback">
    <label for="name">Наименование контрагента</label>
    <input type="text" name="name" class="form-control" id="name" placeholder="Наименование КА" value="<?=isset($_SESSION['receipt']['partner']) ? h($_SESSION['receipt']['partner']) : '';?>" disabled>
</div>
<div class="has-feedback">
    <label for="type">Тип документа для оплаты</label>
    <select class="form-control" name="type" id="type">
        <option value="">Выберите тип</option>
        <option value="ПТ" <?php if ($_SESSION['receipt']['type'] == 'ПТ') { echo ' selected';} ?>>Поступление товаров и услуг</option>
        <option value="ЗП" <?php if ($_SESSION['receipt']['type'] == 'ЗП') { echo ' selected';} ?>>Заказ поставщику</option>
        <option value="АО" <?php if ($_SESSION['receipt']['type'] == 'АО') { echo ' selected';} ?>>Авансовый отчет</option>
    </select>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="date">Дата прихода</label>
        <input type="date" name="date" class="form-control" id="date" placeholder="01.01.2021" value="<?=isset($_SESSION['receipt']['date']) ? h($_SESSION['receipt']['date']) : '';?>" required>
    </div>
    <div class="has-feedback col-6">
        <label for="number">Номер прихода</label>
        <input type="text" name="number" class="form-control" id="number" placeholder="Номер" value="<?=isset($_SESSION['receipt']['number']) ? h($_SESSION['receipt']['number']) : '';?>" required>
    </div>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="sum">Сумма прихода</label>
        <input type="number" name="sum" class="form-control" id="sum"  placeholder="" step="0.01" value="<?=isset($_SESSION['receipt']['sum']) ? h($_SESSION['receipt']['sum']) : '';?>" required>
    </div>
    <div class="has-feedback col-6">
        <label for="vat">НДС</label>
        <select class="form-control" name="vat" id="vat">
            <option value="1.20" <?php if ($_SESSION['receipt']['vat'] == '1.20') { echo ' selected';} ?>>20%</option>
            <option value="1.00" <?php if ($_SESSION['receipt']['vat'] == '1.00') { echo ' selected';} ?>>Без НДС</option>
        </select>
    </div>
</div>
<div class="form-row">
    <div class="has-feedback col-6">
        <label for="num_doc">Номер вх.документа</label>
        <input type="text" name="num_doc" class="form-control" id="num_doc" placeholder="Номер документа" value="<?=isset($_SESSION['receipt']['num_doc']) ? h($_SESSION['receipt']['num_doc']) : '';?>" required>
    </div>
    <div class="has-feedback col-6">
        <label for="date_doc">Дата вх.документа</label>
        <input type="date" name="date_doc" class="form-control" id="date_doc" placeholder="" value="<?=isset($_SESSION['receipt']['date_doc']) ? h($_SESSION['receipt']['date_doc']) : '';?>" required>
    </div>
</div>
<div class="has-feedback">
    <label for="note">Комментарий</label>
    <input type="text" name="note" class="form-control" id="note" placeholder="Комментарий" value="<?=isset($_SESSION['receipt']['note']) ? h($_SESSION['receipt']['note']) : '';?>">
</div>
<input type="hidden" name="partner" value="<?=isset($_SESSION['receipt']['partner']) ? h($_SESSION['receipt']['partner']) : '';?>">
