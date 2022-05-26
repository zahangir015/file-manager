<?php

use app\models\Category;
use app\models\File;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Folders');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'parentId',
                'value' => function ($model) {
                    return ($model->parentId) ? Category::findOne($model->parentId)->name : null;
                },
                'filter' => ArrayHelper::map(Category::findAll(['status' => 1]), 'id', 'name')
            ],
            'name',
            [
                'class' => ActionColumn::className(),
                'template' => '{view}',
                /*'urlCreator' => function ($action, $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },*/
                'buttons' => [
                        'view' => function($url, $model, $key) {
                            return Html::a( '<svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                                                    <path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path>
                                                </svg>',
                            ['file-list', 'categoryId' => Yii::$app->getSecurity()->encryptByKey($model->id, 'MegaMind')]);
                        }
                ]
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
