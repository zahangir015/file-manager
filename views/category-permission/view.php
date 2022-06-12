<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CategoryPermission */

$this->title = $permissionList[0]->user->username;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Folder Permissions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="category-permission-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $permissionList[0]->user->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <table class="table table-bordered">
        <tbody>
        <tr>
            <th>Folder Name</th>
            <th>Created By</th>
            <th>Created At</th>
            <th>Updated By</th>
            <th>Updated At</th>
        </tr>
        <?php
        foreach ($permissionList as $key => $permission) {
            ?>
            <tr>
                <td><?= $permission->refModel::findOne($permission->refId)->name ?></td>
                <td><?= $permission->creator->email ?></td>
                <td><?= $permission->createdAt ?></td>
                <td><?= $permission->updatedBy ?></td>
                <td><?= $permission->updatedAt ?></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>

</div>
