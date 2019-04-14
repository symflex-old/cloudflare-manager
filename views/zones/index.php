<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ZonesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Домены';

$this->registerJsFile(
    '@web/js/zone.js',
    ['depends' => [\yii\web\JqueryAsset::className(), \app\assets\AppAsset::class]]
);

?>
<div class="zones-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <div class="row">
        <div class="col-sm-12">
            <?= Html::a('Добавить домен', ['create'], ['class' => 'btn btn-primary btn-sm']) ?>
            <div data-sync="sync" class="btn btn-success btn-sm pull-right">Синхронизировать</div>
        </div>
    </div>
    <br>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,

        'rowOptions'=>function ($model, $key, $index, $grid){
            $class=$index%2?'odd':'even';
            return [
                'key'=>$key,
                'index'=>$index,
                'class'=>$class
            ];
        },


        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'account_id',
                'filter' => \app\models\Account::dropDown(),
                'value' => function ($data) {
                    return $data->account->email;
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
            ],
            [
                'attribute' => 'domain',
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
            ],
            [
                'attribute' => 'servers',
                'filter' => \app\models\NameServer::dropDown(),
                'value' => function ($data) {
                    $servers = [];
                    foreach ($data->servers as $server) {

                        $servers[] = $server->server;
                    }
                    return implode(PHP_EOL, $servers);
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
            ],
            [
                'attribute' => 'dns',
                'filter' => \app\models\DnsRecord::dropDown(),
                'value' => function ($data) {
                    return isset($data->dns[0]) ? $data->dns[0]->value : '';
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
                'contentOptions' => function ($model, $key, $index, $column) {
                    return [
                        'data-zone-dns' => $key
                    ];
                }
            ],
            [
                'attribute' => 'tls',
                'filter' => \app\models\Zones::TLS,
                'options' => ['width' => '180'],
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
                'format' => 'raw',
                'value' => function($data) {
                    $options = [];
                    foreach (\app\models\Zones::TLS as $key => $tls) {
                        $selected = '';

                        if ($data->tls === $key) {
                            $selected = 'selected';
                        }
                        $options[] = '<option value="'.$key.'" '.$selected.'>'.$tls.'</option>';
                    }
                    return '<select data-action="tls" data-id="'.$data->id.'" class="form-control input-sm">'.implode('', $options).'</select>';
                },
                'headerOptions' => ['width' => '150']
            ],
            [
                'attribute' => 'ssl',
                'filter' => \app\models\Zones::ON_OFF,
                'format' => 'raw',
                'value' => function($data) {
                    $checked = $data->ssl === 1 ? 'checked' : '';
                    return '<input data-action="ssl" data-id="'.$data->id.'" type="checkbox" data-toggle="toggle" data-size="small" '.$checked.'>';
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
                'headerOptions' => ['width' => '90']
            ],
            [
                'attribute' => 'rewrite',
                'filter' => \app\models\Zones::ON_OFF,
                'format' => 'raw',
                'value' => function($data) {
                    $checked = $data->rewrite === 1 ? 'checked' : '';
                    return '<input data-action="rewrite" data-id="'.$data->id.'" type="checkbox" data-toggle="toggle" data-size="small" '.$checked.'>';

                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
                'headerOptions' => ['width' => '90']
            ],
            [
                'attribute' => 'debug',
                'filter' => \app\models\Zones::ON_OFF,
                'format' => 'raw',
                'value' => function($data) {
                    $checked = $data->debug === 1 ? 'checked' : '';
                    return '<input data-action="debug" data-id="'.$data->id.'" type="checkbox" data-toggle="toggle" data-size="small" '.$checked.'>';
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
                'headerOptions' => ['width' => '90']
            ],
            [
                'attribute' => 'attack_mode',
                'filter' => \app\models\Zones::ON_OFF,
                'format' => 'raw',
                'value' => function($data) {
                    $checked = $data->attack_mode === 1 ? 'checked' : '';
                    return '<input data-action="attack" data-id="'.$data->id.'" type="checkbox" data-toggle="toggle" data-size="small" '.$checked.'>';
                },
                'filterInputOptions' => [
                    'class' => 'form-control input-sm'
                ],
                'headerOptions' => ['width' => '90']
            ],
            [
                'label' => 'Cache',
                'format' => 'raw',
                'value' => function($data) {
                    return '<a data-action="cache" data-id="'.$data->id.'" class="btn btn-warning btn-sm">Flush</a>';
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'#',
                'headerOptions' => ['width' => '60'],
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url,$model) {
                        return '<a data-action="delete" data-id="'.$model->id.'" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-trash"></i></a>';
                    },
                ]
            ],
        ],
    ]); ?>


</div>



<script>
    let tls = JSON.parse('<?= json_encode(\app\models\Zones::TLS) ?>');
    let ttl = JSON.parse('<?= json_encode(\app\models\Zones::RECORD_TTL) ?>');
</script>

<div class="modal fade" id="ipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover table-striped">
                    <tr>
                        <td width="62">
                            <select class="form-control input-sm" style="padding-left: 5px;" data-insert="type">
                                <option value="A">A</option>
                            </select>
                        </td>
                        <td width="250">
                            <input type="text" name="name" placeholder="name" class="form-control input-sm" data-insert="name">
                        </td>
                        <td width="250">
                            <input type="text" name="content" placeholder="value" class="form-control input-sm" data-insert="content">
                        </td>
                        <td width="152">
                            <select class="form-control input-sm" data-insert="ttl">
                                <?php foreach (\app\models\Zones::RECORD_TTL as $key => $ttl): ?>
                                    <option value="<?= $key ?>"><?= $ttl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td width="84">
                            <input type="checkbox" data-toggle="toggle" data-size="small" data-insert="status" checked>
                        </td>
                        <td width="69">
                            <div class="btn btn-primary btn-sm" id="add-record">Add</div>
                        </td>
                    </tr>
                </table>

                <form>
                    <table class="table table-bordered table-hover table-striped" id="rows">
                        <thead>
                        <tr><th>type</th><th>name</th><th>value</th><th>ttl</th></th><th>status</th><th>#</th></tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </form>
                <label id="error-ip" style="display: none">не верный ip</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Закрыть</button>
            </div>
        </div>
    </div>
</div>
