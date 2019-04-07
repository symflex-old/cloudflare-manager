<?php
/* @var $this yii\web\View */
?>
<h1>Добавить домен</h1>

<?php
/* @var $this yii\web\View */
?>


<form>
    <div class="form-group">
        <label>Аккаунт</label>
        <select class="form-control input-sm">
            <option>test@test.ru</option>
            <option>test2@test.ru</option>
            <option>test3@test.ru</option>
        </select>
    </div>

    <div class="form-group">
        <label>Домен</label>
        <input class="form-control input-sm" name="name">
    </div>

    <div class="form-group">
        <button class="btn btn-success btn-sm">Создать</button>
    </div>
</form>


