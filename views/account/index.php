<?php
/* @var $this yii\web\View */
?>
<h1>Управление аккаунтами</h1>

<?php
/* @var $this yii\web\View */

$this->registerJsFile(
    '@web/js/account.js',
    ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\AppAsset::class]]
);

?>

<div class="create">
    <div class="row">
        <div class="col-sm-4">
            <a id="create" class="btn btn-success btn-sm">Добавить</a>
        </div>
        <div class="col-sm-8">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Аккаунт успешно обновлен
            </div>

            <!--<div class="alert alert-danger alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Ошибка
            </div>-->
        </div>
    </div>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th width="30">ID</th>
        <th>E-mail</th>
        <th>Api key</th>
        <th class="act">#</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($accounts as $account): ?>
        <tr>
            <form>
            <td><?= $account->id ?></td>
            <td><input class="form-control input-sm" name="email" value="<?= $account->email ?>"></td>
            <td><input class="form-control input-sm" name="api_key" value="<?= $account->api_key ?>"></td>
            <td class="act">
                <button class="btn btn-danger btn-sm" data-action="delete" data-id="<?= $account->id ?>"><i class="glyphicon glyphicon-trash"></i></button>
                <button class="btn btn-success btn-sm" data-action="save" data-id="<?= $account->id ?>"><i class="glyphicon glyphicon-floppy-saved"></i></button>
            </td>
            </form>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
