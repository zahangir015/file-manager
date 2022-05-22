<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryPermission */

$this->title = Yii::t('app', 'Update Folder Permission: {name}', [
    'name' => $userPermissionList[0]->user->username,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Folder Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="category-permission-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'userPermissionList' => $userPermissionList
    ]) ?>

</div>
