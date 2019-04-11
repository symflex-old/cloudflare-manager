<?php
/* @var $this yii\web\View */
?>
<h1>Добавить домен</h1>

<?php
/* @var $this yii\web\View */
?>


<form method="post">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <div class="form-group">
        <label>Аккаунт</label>
        <select class="form-control input-sm" name="account">
            <?php foreach ($accounts as $account): ?>
            <option value="<?= $account->id ?>"><?= $account->email ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Домен</label>
        <input class="form-control input-sm" name="domain">
    </div>

    <div class="form-group">
        <button class="btn btn-success btn-sm">Создать</button>
    </div>
</form>


