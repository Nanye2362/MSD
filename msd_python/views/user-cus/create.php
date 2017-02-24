<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\UserCus */

$this->title = 'Create User Cus';
$this->params['breadcrumbs'][] = ['label' => 'User Cuses', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-cus-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
