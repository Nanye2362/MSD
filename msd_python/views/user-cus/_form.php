<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserCus */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-cus-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'vip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'aias')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qudao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fengongsi')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'jiatingjiegou')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
