<?php

/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    echo Nav::widget([
        'items' => [
            [
                'label' => 'File Management',
                'url' => ['/file/index'],
                'visible' => !Yii::$app->user->isGuest
            ],
            [
                'label' => 'User Management',
                'items' => [
                    ['label' => 'Users', 'url' => '/admin/user'],
                    ['label' => 'Assignments', 'url' => '/admin/assignment'],
                    ['label' => 'Roles', 'url' => '/admin/role'],
                    ['label' => 'Permissions', 'url' => '/admin/permission'],
                    ['label' => 'Routes', 'url' => '/admin/route'],
                    ['label' => 'Rules', 'url' => '/admin/rule'],
                ],
                'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->can('User Management'))
            ],
            [
                'label' => 'Login',
                'url' => ['/admin/user/login'],
                'visible' => Yii::$app->user->isGuest
            ],

            [
                'label' => 'Logout',
                'url' => ['/admin/user/logout'],
                'linkOptions' => ['data-method' => 'post'],
                'visible' => !Yii::$app->user->isGuest && (Yii::$app->user->can('File Management')),
            ],
        ],
        'options' => ['class' =>'nav-pills'], // set this to nav-tab to get tab-styled navigation
    ]);
    /*echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => [
            Yii::$app->user->isGuest ? (
            ['label' => 'Login', 'url' => ['/admin/user/login']]
            ) : (
                '<li>' .Html::a('User Management', ['/admin/user'], ['class' => 'form-inline btn btn-link']).'</li>' .
                '<li>' .Html::a('File Management', ['/file/index'], ['class' => 'form-inline btn btn-link']).'</li>' .
                '<li>'
                . Html::beginForm(['/admin/user/logout'], 'post', ['class' => 'form-inline'])
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);*/
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-left">&copy; Eskayef Pharmaceuticals Limited  <?= date('Y') ?></p>
        <!--<p class="float-right"><?/*= Yii::powered() */?></p>-->
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
