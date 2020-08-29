<?php

use yii\helpers\Html;
use common\models\User;

$user = new User();
?>
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="<?= \yii\helpers\Url::toRoute(['/']) ?>" class="nav-link">Главная</a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <?= Html::a('<i class="fas fa-sign-out-alt"></i>', ['site/logout'], ['data-method' => 'post', 'class' => 'nav-link']) ?>
        </li>
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
               <span class="d-none d-md-inline"><?= $user->getName() ?></span>
            </a>
            <ul class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                <!-- User image -->
                <li style="padding: 10px">
                    <p>
                       Hi, <?= $user->getName() ?>!
                    </p>
                    <?= Html::a('Выйти', ['site/logout'], ['data-method' => 'post', 'class' => 'btn btn-default btn-flat justify-content-center']) ?>

                </li>
            </ul>
        </li>

    </ul>
</nav>