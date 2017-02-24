<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserCusSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'User Cuses';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-cus-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User Cus', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
           // 'id',
            'vip',
            'aias',
            'qudao',
            'fengongsi',
            'jiatingjiegou',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
