<?php
/* @var $this yii\web\View */
?>
<h1>Управление аккаунтами</h1>

<?php
/* @var $this yii\web\View */
?>

<style>
    .create {
        margin: 10px 0 10px 0;
    }

    .act {
        width: 120px;
        text-align: center;
    }
    .alert {
        padding: 5px 15px;
        margin-bottom: 0;
    }
    .alert-dismissable .close,
    .alert-dismissible .close {
        right: 0;
    }
</style>
<script>
    $(function() {
        $('#create').on('click', function (e) {
            e.preventDefault();

            let template = '<tr>' +
                '        <td>' +
                '        </td>' +
                '        <td>' +
                '            <input class="form-control input-sm" name="email">' +
                '        </td>' +
                '        <td>' +
                '            <input class="form-control input-sm" name="api_key">' +
                '        </td>' +
                '        <td class="act">' +
                '            <button class="btn btn-danger btn-sm" id="delete" data-action="delete"><i class="glyphicon glyphicon-trash"></i> </button>' +
                '            <button class="btn btn-success btn-sm" id="save" data-action="save"><i class="glyphicon glyphicon-floppy-saved"></i> </button>' +
                '        </td>' +
                '    </tr>';
            $('table tbody').prepend(template);

        });

        $('table').on('click', '[data-action="save"]', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let parent = $(this).parent().parent();

            let email = $(parent).find('[name="email"]').val();
            let apiKey = $(parent).find('[name="api_key"]').val();

            let action = 'create';

            if (id) {
                action = 'update?id=' + id;
            }

            $.ajax({
                'url': '/account/' + action,
                'method': 'post',
                'dataType': 'json',
                'data': {Account:{email:email, api_key: apiKey}},
            }).done(function (e) {
                console.log(e)
            });

        });


    });
</script>

<div class="create">
    <div class="row">
        <div class="col-sm-4">
            <a id="create" class="btn btn-success btn-sm">Добавить</a>
        </div>
        <div class="col-sm-8">
            <div class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                Успешно
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
