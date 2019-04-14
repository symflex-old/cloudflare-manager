<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Zones */

$this->title = 'Create Zones';
$this->params['breadcrumbs'][] = ['label' => 'Zones', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zones-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
