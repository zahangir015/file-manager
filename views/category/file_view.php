<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\web\YiiAsset;
use yii\widgets\DetailView;
use yii2assets\pdfjs\PdfJs;

/* @var $this yii\web\View */
/* @var $model app\models\File */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Files'), 'url' => ['file-list', 'categoryId' => Yii::$app->security->encryptByKey($model->categoryId, 'MegaMind')]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$this->registerJsFile(
    '@web/js/pdf_viewer.js',
    ['depends' => [JqueryAsset::className()]]
);
?>
<div class="file-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=  Html::button('button', ['hidden' => true, 'id' => 'trampCard'])?>
    <?= PdfJs::widget([
        'url' => Url::base() . $model->path,
        'buttons' => [
            'presentationMode' => true,
            'openFile' => false,
            'print' => false,
            'download' => false,
            'viewBookmark' => true,
            'secondaryToolbarToggle' => true
        ],
    ]); ?>

</div>
