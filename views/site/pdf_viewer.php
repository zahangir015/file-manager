<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'About';
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile(
    '@web/js/pdf_viewer.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);

?>
<div class="site-pdf-viewer">
    <?= \yii2assets\pdfjs\PdfJs::widget([
        'url' => Url::base() . '/uploads/multiple_page.pdf',
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
