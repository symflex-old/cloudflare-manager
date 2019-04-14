<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ZonesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Zones';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zones-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Zones', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'account_id',
                'filter' => \app\models\Account::dropDown(),
                'value' => function ($data) {

                    return $data->account->email;

                }
            ],
            'domain',
            [
                'attribute' => 'ssl',
                'filter' => \app\models\Zones::ON_OFF
            ],
            [
                'attribute' => 'nameServers',
                'filter' => \app\models\NameServer::dropDown(),
                'content' => function ($data) {
                    $servers = [];
                    foreach ($data->nameServers as $server) {

                        $servers[] = $server->server;
                    }
                    return implode(PHP_EOL, $servers);
                }
            ],
            [
                'attribute' => 'dnsRecords',
                'filter' => \app\models\DnsRecord::dropDown(),
                'value' => function ($data) {
                    return $data->dnsRecords[0]->value;
                }

            ],
            [
                'attribute' => 'tls',
                'filter' => \app\models\Zones::TLS
            ],
            [
                'attribute' => 'rewrite',
                'filter' => \app\models\Zones::ON_OFF
            ],
            [
                'attribute' => 'debug',
                'filter' => \app\models\Zones::ON_OFF
            ],
            [
                'attribute' => 'attack_mode',
                'filter' => \app\models\Zones::ON_OFF
            ],

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
