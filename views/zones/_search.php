<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ZonesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="zones-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'account_id') ?>

    <?= $form->field($model, 'domain') ?>

    <?= $form->field($model, 'ssl') ?>

    <?= $form->field($model, 'tls') ?>

    <?php // echo $form->field($model, 'rewrite') ?>

    <?php // echo $form->field($model, 'debug') ?>

    <?php // echo $form->field($model, 'attack_mode') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
