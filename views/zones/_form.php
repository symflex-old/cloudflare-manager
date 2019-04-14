<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Zones */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="zones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'account_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\app\models\Account::find()->asArray()->all(), 'id', 'email')
    )->label('Аккаунт') ?>

    <?= $form->field($model, 'domain')->textInput(['maxlength' => true])->label('Домен') ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
