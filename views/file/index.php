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
/* @var $searchModel app\models\FileSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Files');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="file-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Upload File'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'attribute' => 'categoryId',
                'label' => 'Folder',
                'value' => function ($model) {
                    return $model->category->name ?? null;
                },
                'filter' => ArrayHelper::map(Category::find()->where(['status' => 1])->all(), 'id', 'name')
            ],
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    return $model->status ? '<span class="label label-lg label-light-primary label-inline">Active</span>' : '<span class="label label-lg label-light-danger label-inline">Inactive</span>';
                },
                'format' => 'html',
                'filter' => ['Inactive', 'Active']
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
                'urlCreator' => function ($action, File $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
