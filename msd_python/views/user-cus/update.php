<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\UserCus */

$this->title = 'Update User Cus: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'User Cuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-cus-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
