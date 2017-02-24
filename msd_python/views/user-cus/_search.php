<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserCusSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-cus-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'vip') ?>

    <?= $form->field($model, 'aias') ?>

    <?= $form->field($model, 'qudao') ?>

    <?php // echo $form->field($model, 'fengongsi') ?>

    <?php // echo $form->field($model, 'jiatingjiegou') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
