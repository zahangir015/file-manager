<?php

use app\models\Category;
use kartik\file\FileInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\File */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="file-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'categoryId')->dropdownList(ArrayHelper::map(Category::findAll(['status' => 1]), 'id', 'name'), ['maxlength' => true]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'status')->dropDownList(['Active', 'Inactive']) ?>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'files[]')->widget(FileInput::classname(), [
                'options' => [
                    'multiple' => true,
                    'accept' => '*'
                ],
                'pluginOptions' => [
                    //'uploadUrl' => Url::to(['/site/file-upload']),
                    'maxFileCount' => 10,
                    'showUploadStats' => true,
                ]
            ])->label('Upload files');
            ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
