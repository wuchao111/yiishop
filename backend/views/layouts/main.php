<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => '商城后台管理系统',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems[] = [
        'label' => '首页', 'url' => ['#'],
        ];
//       $menuItems[] = [
//            'label' => '用户管理', 'url' => ['#'],
//           'items' => [
//               ['label' => '添加用户', 'url' => ['/admin/add']],
//               ['label' => '用户列表', 'url' => ['/admin/index']],
//           ],
//        ];$menuItems[] = [
//            'label' => '品牌管理', 'url' => ['#'],
//            'items' => [
//                ['label' => '添加品牌', 'url' => ['/brand/add']],
//                ['label' => '品牌列表', 'url' => ['/brand/index']],
//            ],
//        ];
//       $menuItems[] = [
//                'label' => '文章管理', 'url' => ['#'],
//            'items' => [
//                ['label' => '添加文章分类', 'url' => ['/article-category/add']],
//                ['label' => '文章分类列表', 'url' => ['/article-category/index']],
//                ['label'=> '添加文章','url' => ['/article/add']],
//                ['label'=> '文章列表','url' => ['/article/index']],
//            ],
//
//        ];$menuItems[] = [
//            'label' => 'RBAC', 'url' => ['#'],
//            'items' => [
//                ['label' => '添加权限', 'url' => ['/rbac/addper']],
//                ['label' => '权限列表','url' => ['/rbac/indexper']],
//                ['label'=> '添加角色','url' => ['/rbac/addrole']],
//                ['label'=> '角色列表','url' => ['/rbac/indexrole']],
//            ],
//
//        ];$menuItems[] = [
//            'label' => '商品管理', 'url' => ['#'],
//            'items' => [
//                ['label' => '添加商品', 'url' => ['/goods/add']],
//                ['label' => '商品列表', 'url' => ['/goods/index']],
//                ['label'=> '添加商品分类','url' => ['/goods-category/add']],
//                ['label'=> '商品分类列表','url' => ['/goods-category/index']],
//            ],
//        ];$menuItems[] = [
//            'label' => '菜单管理', 'url' => ['#'],
//            'items' => [
//                ['label' => '添加菜单', 'url' => ['/menu/add']],
//                ['label' => '菜单列表', 'url' => ['/menu/index']],
//            ],
//        ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => '登录', 'url' => ['/admin/login']];
    } else {
        $menuItems = \backend\models\Menu::getMenus($menuItems);
        $menuItems[] = [
            'label' => '' . Yii::$app->user->identity->username . '',
            'items' => [
                ['label' => '修改密码', 'url' => ['/admin/edits']],
                ['label'=> '退出登录','url' => ['admin/logout']],
            ],
        ];

    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
