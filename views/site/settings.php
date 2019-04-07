<?php


?>

<form action="/site/settings" method="post">
    <input type="hidden" name="<?=Yii::$app->request->csrfParam; ?>" value="<?=Yii::$app->request->getCsrfToken(); ?>" />
    <div class="form-group">
        <label>Логин</label>
        <input name="login" class="form-control input-sm" value="<?= $user->username ?>">
    </div>
    <div class="form-group">
        <label>Пароль</label>
        <input name="password" class="form-control input-sm">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary btn-sm">Сохранить</button>
    </div>
</form>