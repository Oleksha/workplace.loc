<main role="main" class="flex-shrink-0">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php if (isset($_SESSION['errors'])) : ?>
                    <div class="alert alert-danger">
                        <?php echo $_SESSION['errors']; unset($_SESSION['errors']); ?>
                    </div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])) : ?>
                    <div class="alert alert-success">
                        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <h1 class="mt-1">Добавление нового контрагента</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?=PATH;?>">Главная</a></li>
                <li class="breadcrumb-item"><a href="<?=PATH;?>/partner">Список контрагентов</a></li>
                <li class="breadcrumb-item active" aria-current="page">Добавление нового контрагента</li>
            </ol>
        </nav>
        <div class="row d-flex justify-content-center">
            <div class="col-9">
                <form method="post" action="partner/add" id="ka_add" role="form" class="was-validated">
                    <div class="has-feedback">
                        <label for="name">Наименование контрагента</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Наименование КА" value="<?=isset($_SESSION['form_data']['name']) ? h($_SESSION['form_data']['name']) : '';?>" required>
                    </div>
                    <div class="form-row">
                        <div class="has-feedback col-4">
                            <label for="alias">Номер</label>
                            <input type="text" name="alias" class="form-control" id="alias" placeholder="Номер" value="<?=isset($_SESSION['form_data']['alias']) ? h($_SESSION['form_data']['alias']) : '';?>" required>
                        </div>
                        <div class="has-feedback col-4">
                            <label for="inn">ИНН</label>
                            <input type="text" name="inn" class="form-control" id="inn" placeholder="ИНН" value="<?=isset($_SESSION['form_data']['inn']) ? h($_SESSION['form_data']['inn']) : '';?>" required>
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
                                <option value="">Выберите...</option>
                                <option value="1.20" selected>20%</option>
                                <option value="1.00">Без НДС</option>
                            </select>
                        </div>
                    </div>
                    <div class="row d-flex justify-content-center mt-3">
                        <button type="submit" class="btn btn-primary">Сохранить данные КА</button>
                    </div>
                </form>
                <?php if(isset($_SESSION['form_data'])) unset($_SESSION['form_data']); ?>
            </div>
        </div>

    </div>
</main>
