<?php
/* @var $this yii\web\View */

$tlsList = [
    '1.0',
    '1.1',
    '1.2',
    '1.3',
];

$sslList = ['off', 'full', 'flexible', 'strict'];
?>

<style>
    .create {
        margin: 10px 0 10px 0;
    }
    th, td {
        text-align: center;
    }

</style>

<h1>Управление доменами</h1>

<div class="create">
    <a href="/domain/create" class="btn btn-success btn-sm">Добавить</a>
</div>

<table class="table table-bordered table-hover table-striped">
    <thead>
    <tr>
        <th width="80">ID</th>
        <th>Логин</th>
        <th>Домен</th>
        <th>NS-записи</th>
        <th>ДНС-записи</th>
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
            <td><?= $zone['dns'] ?></td>
            <td>
                <select class="form-control input-sm">
                <?php foreach ($sslList as $item): ?>
                    <option value="<?= $item ?>" <?= $item === $zone['ssl'] ? 'selected' : '' ?>><?= ucfirst($item) ?> </option>
                <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select class="form-control input-sm">
                <?php foreach ($tlsList as $item): ?>
                <option value="<?= $item ?>" <?= $item === $zone['tls'] ? 'selected' : '' ?>>TLS <?= $item ?> </option>
                <?php endforeach; ?>
                </select>
            </td>
            <td><input data-action="rewrite-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['rewrite'] ? 'checked' : '' ?>> </td>
            <td><a class="btn btn-danger btn-sm">FLUSH</a> </td>
            <td><input data-action="dev-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['dev'] ? 'checked' : '' ?>> </td>
            <td><input data-action="sec_level-<?= $i ?>" data-id="<?= $zone['id'] ?>" data-account="<?= $zone['account'] ?>" type="checkbox" data-toggle="toggle" data-size="small" <?php echo $zone['sec_level'] ? 'checked' : '' ?>> </td>
            <td>
                <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>

</table>
