<main role="main" class="flex-shrink-0">
    <div class="container">
        <div class="d-flex justify-content-between">
            <h1 class="mt-1">Список бюджетных операций</h1>
        </div>

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?=PATH;?>">Главная</a></li>
                    <!--<li class="breadcrumb-item"><a type="button" href="<?=PATH;?>/partner/add">Добавить КА</a></li>-->
                    <li class="breadcrumb-item active" aria-current="page">Бюджетные операции</li>
            </ol>
        </nav>

        <?php if($budgets): ?>
            <table id="bo_view" class="display" style="width:100%">
                <thead>
                <tr>
                    <th>Сценарий</th>
                    <th>МР</th>
                    <th>МО</th>
                    <th>Номер</th>
                    <th>Сумма</th>
                    <th>НДС</th>
                    <th>Статья</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($budgets as $budget): ?>
                    <tr>
                        <td><?= $budget->scenario;?></td><!--<a href="partner/<?= $partner->inn;?>"><?= $partner->name;?></a>-->
                        <td><?= $budget->month_exp;?></td>
                        <td><?= $budget->month_pay;?></td>
                        <td><?= $budget->number;?></td>
                        <td><?= number_format($budget->summa, 2, ',', '&nbsp;');?>&nbsp;₽</td>
                        <td><?= $budget->vat;?></td>                        
                        <td><?= $budget->budget_item;?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>
