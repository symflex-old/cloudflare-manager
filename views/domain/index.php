<?php
/* @var $this yii\web\View */
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
        <th width="80">
            ID
        </th>
        <th>
            Логин
        </th>
        <th>
            Домен
        </th>
        <th>
            NS-записи
        </th>
        <th>
            ДНС-записи
        </th>
        <th>
            SSL
        </th>
        <th>
            TLS
        </th>
        <th>
           Rewrites
        </th>
        <th>
            Кеш
        </th>

        <th>
            Dev режим
        </th>
        <th>
            Attack Mode
        </th>
        <th width="80">
            #
        </th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    <tr>
        <td>1</td>
        <td>test@test.ru</td>
        <td>test.ru</td>
        <td>127.0.0.1</td>
        <td>A 127.0.0.1</td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-warning btn-sm">FLUSH</a> </td>
        <td><a class="btn btn-primary btn-sm">OFF</a> </td>
        <td><a class="btn btn-success btn-sm">ON</a> </td>
        <td>
            <a class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i> </a>
        </td>
    </tr>
    </tbody>

</table>
