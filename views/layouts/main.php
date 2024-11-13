<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top']
    ]);

    $items = [];

    // Add "Users" and "Create User" links if user_type is 1
    if (Yii::$app->user->identity && Yii::$app->user->identity->user_type == Yii::$app->params['roleAdmin']) {
        $items[] = ['label' => 'Users', 'url' => ['/user/index']];

        $items[] = [
            'label' => 'Categories',
            'url' => ['/category/index']
        ];
        $items[] = [
            'label' => 'Products',
            'url' => ['/product/index']
        ];

    } else if (Yii::$app->user->identity && Yii::$app->user->identity->user_type == Yii::$app->params['roleUser']) {
        // specify what normal user can see
        $items[] = [
            'label' => 'Profile',
            'url' => ['/user/view', 'id' => Yii::$app->user->identity->id]
        ];

        $items[] = [
            'label' => 'Categories',
            'url' => ['/category/index']
        ];
        $items[] = [
            'label' => 'Products',
            'url' => ['/product/index']
        ];
        
    } else {
        // Add "Signup" link for guests
        $items[] = ['label' => 'Signup', 'url' => ['/user/create']];
    }

    // Add "Login" link for guests or "Logout" form for authenticated users
    if (Yii::$app->user->isGuest) {
        $items[] = ['label' => 'Login', 'url' => ['/user/login']];
    } else {
        $items[] = [
            'label' => 'Logout (' . Yii::$app->user->identity->user_name . ')',
            'url' => '#', // Placeholder URL
            'linkOptions' => [
                'class' => 'nav-link btn btn-link logout',
                'onclick' => 'event.preventDefault(); document.getElementById("logout-form").submit();',
            ],
        ];
        // Form for logout
        echo Html::beginForm(['/user/logout'], 'post', ['id' => 'logout-form']) .
            Html::endForm();
    }

    // Render the navigation
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav'],
        'items' => $items,
    ]);
    NavBar::end();

    
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; Cord4 <?= date('Y') ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
