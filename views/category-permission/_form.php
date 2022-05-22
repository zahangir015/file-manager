<?php

use mdm\admin\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryPermission */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="category-permission-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'userId')->dropDownList(ArrayHelper::map(User::find()->all(), 'id', 'username')) ?>
    <?= $form->field($model, 'refId')->dropDownList($categories, ['maxlength' => true, 'multiple' => 'multiple', 'value' => isset($userPermissionList) ? ArrayHelper::map($userPermissionList, 'id', 'refId') : []]) ?>
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
