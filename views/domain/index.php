<?php
/* @var $this yii\web\View */

$tlsList = [
    '1.0',
    '1.1',
    '1.2',
    '1.3',
];

$sslList = ['off', 'full', 'flexible', 'strict'];

$this->registerJsFile(
    '@web/js/domain.js',
    ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\AppAsset::class]]
);
?>

<h1>Управление доменами</h1>

<div class="create">
    <div class="row">
        <div class="col-sm-4">
            <a href="/domain/create" class="btn btn-success btn-sm">Добавить</a>
        </div>
        <div class="col-sm-8">
           <div id="alert">

           </div>
        </div>
    </div>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th width="80">ID</th>
        <th>Логин</th>
        <th>Домен</th>
        <th>NS-записи</th>
        <th>DNS A - запись</th>
        <th>SSL</th>
        <th width="110">TLS</th>
        <th>Rewrites</th>
        <th>Кеш</th>
        <th>Dev режим</th>
        <th>Attack Mode</th>
        <th width="80">#</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($zones as $i => $zone): ?>
        <tr>
            <td><?= ++$i ?></td>
            <td><?= $zone['account'] ?></td>
            <td><?= $zone['domain'] ?></td>
            <td><?= implode('<br>', $zone['ns']) ?></td>
            <td class="ip" data-action="ip-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" data-domain="<?= $zone['domain'] ?>" name="ip" data-record="<?= $zone['dns_id'] ?>"><?= $zone['dns'] ?></td>
            <td>
                <input data-action="ssl-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['ssl'] ? 'checked' : '' ?>>
            </td>
            <td width="110">
                <select class="form-control input-sm" data-action="tls-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" style="width:110px;">
                <?php foreach ($tlsList as $item): ?>
                <option value="<?= $item ?>" <?= $item === $zone['tls'] ? 'selected' : '' ?>>TLS <?= $item ?> </option>
                <?php endforeach; ?>
                </select>
            </td>
            <td><input data-action="rewrite-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['rewrite'] ? 'checked' : '' ?>> </td>
            <td><a data-action="purge-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" class="btn btn-danger btn-sm">FLUSH</a> </td>
            <td><input data-action="dev-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['dev'] ? 'checked' : '' ?>> </td>
            <td><input data-action="sec_level-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['sec_level'] ? 'checked' : '' ?>> </td>
            <td>
                <a data-action="delete-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>

<div class="modal fade" id="ipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input class="form-control input-sm" name="ip" type="text" value="">
                <label id="error-ip" style="display: none">не верный ip</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Отмена</button>
                <button type="button" class="btn btn-primary btn-sm" id="saveIp">Сохранить</button>
            </div>
        </div>
    </div>
</div>
