<input type="hidden" name="id_ka" value="<?=isset($_SESSION['form_data']['id']) ? h($_SESSION['form_data']['id']) : '';?>">
<div class="has-feedback">
    <label for="name">Наименование контрагента</label>
    <input type="text" name="name" class="form-control" id="name" placeholder="Наименование КА" value="<?=isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : '';?>" required>
</div>
<div class="form-row">
    <div class="has-feedback col-4">
        <label for="alias">Номер</label>
        <input type="text" name="alias" class="form-control" id="alias" placeholder="Номер" value="<?=isset($_SESSION['form_data']['alias']) ? h($_SESSION['form_data']['alias']) : '';?>" disabled>
    </div>
    <div class="has-feedback col-4">
        <label for="inn">ИНН</label>
        <input type="text" name="inn" class="form-control" id="inn" placeholder="ИНН" value="<?=isset($_SESSION['form_data']['inn']) ? h($_SESSION['form_data']['inn']) : '';?>" disabled>
    </div>
    <div class="has-feedback col-4">
        <label for="kpp">КПП</label>
        <input type="text" name="kpp" class="form-control" id="kpp" placeholder="КПП" value="<?=isset($_SESSION['form_data']['kpp']) ? h($_SESSION['form_data']['kpp']) : '';?>">
    </div>
</div>
<div class="has-feedback">
    <label for="bank">Наименование обслуживающего банка</label>
    <input type="text" name="bank" class="form-control" id="bank" placeholder="Наименование банка" value="<?=isset($_SESSION['form_data']['bank']) ? h($_SESSION['form_data']['bank']) : '';?>">
</div>
<div class="form-row">
    <div class="has-feedback col-4">
        <label for="bic">БИК</label>
        <input type="text" name="bic" class="form-control" id="bic" placeholder="БИК" value="<?=isset($_SESSION['form_data']['bic']) ? h($_SESSION['form_data']['bic']) : '';?>">
    </div>
    <div class="has-feedback col-8">
        <label for="account">Номер расчетного счета</label>
        <input type="text" name="account" class="form-control" id="account" placeholder="Номер расчетного счета" value="<?=isset($_SESSION['form_data']['account']) ? h($_SESSION['form_data']['account']) : '';?>">
    </div>
</div>
<div class="has-feedback">
    <label for="address">Юридический адрес</label>
    <input type="text" name="address" class="form-control" id="address" placeholder="Юридический адрес" value="<?=isset($_SESSION['form_data']['address']) ? h($_SESSION['form_data']['address']) : '';?>">
</div>
<div class="form-row">
    <div class="has-feedback col-3">
        <label for="phone">Телефоны</label>
        <input type="text" name="phone" class="form-control" id="phone" placeholder="Телефоны" value="<?=isset($_SESSION['form_data']['phone']) ? h($_SESSION['form_data']['phone']) : '';?>">
    </div>
    <div class="has-feedback col-3">
        <label for="email">E-mail</label>
        <input type="text" name="email" class="form-control" id="email" placeholder="E-mail" value="<?=isset($_SESSION['form_data']['email']) ? h($_SESSION['form_data']['email']) : '';?>">
    </div>
    <div class="has-feedback col-3">
        <label for="delay">Отсрочка</label>
        <input type="text" name="delay" class="form-control" id="delay" placeholder="Отсрочка" value="<?=isset($_SESSION['form_data']['delay']) ? h($_SESSION['form_data']['delay']) : '';?>">
    </div>
    <div class="has-feedback col-3">
        <label for="vat">НДС</label>
        <select class="form-control" name="vat" id="vat">
            <?php if (!empty($_SESSION['form_data']['vat'])) : ?>
                <?php if ($_SESSION['form_data']['vat'] == '1.20') : ?>
                    <option value="1.20" selected>20%</option>
                    <option value="1.00">Без НДС</option>
                <?php elseif ($_SESSION['form_data']['vat'] == '1.00') : ?>
                    <option value="1.20">20%</option>
                    <option value="1.00" selected>Без НДС</option>
                <?php endif; ?> 
            <?php else : ?>
                <option value="">Выберите...</option>
                <option value="1.20">20%</option>
                <option value="1.00">Без НДС</option>
            <?php endif; ?>
        </select>
    </div>
</div>
<input type="hidden" name="alias" value="<?=isset($_SESSION['form_data']['alias']) ? h($_SESSION['form_data']['alias']) : '';?>">
<input type="hidden" name="inn" value="<?=isset($_SESSION['form_data']['inn']) ? h($_SESSION['form_data']['inn']) : '';?>">
