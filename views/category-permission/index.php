<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategoryPermissionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Folder Permissions');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-permission-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Folder Permission'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'userId',
                'value' => function ($model) {
                    return $model->user->username;
                }
            ],
            [
                'attribute' => 'creator',
                'value' => function ($model) {
                    return $model->creator->email;
                },
            ],
            'createdAt:date',
            'updatedBy',
            'updatedAt',
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, \app\models\CategoryPermission $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->userId]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
