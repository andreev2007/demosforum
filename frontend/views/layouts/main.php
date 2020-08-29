<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\models\Notifications;
use yii\helpers\Html;
use yii\helpers\Url;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\User;

$user = new User();
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="<?= Html::encode(Url::to(Yii::$app->homeUrl)) ?>"><?= Yii::$app->name ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active active">
                <a class="nav-link" href="<?= Html::encode(Url::to('/site/index')) ?>">Главная</a>
            </li>
            <?php if (User::isAdmin()) { ?>
                <li class="nav-item active">
                    <a class="nav-link" href="<?= Html::encode(Url::to('/admin')) ?>">Админ панель</a>
                </li>
            <?php } ?>

            <?php if (Yii::$app->user->isGuest) { ?>
                <li class="nav-item active mr-3">
                    <a class="nav-link btn btn-info" href="<?= Html::encode(Url::to('/site/signup')) ?>">Присоединиться
                        <i class="fas fa-user-plus"></i></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link btn btn-outline-secondary" href="<?= Html::encode(Url::to('/site/login')) ?>">Войти
                        <i class="fas fa-sign-in"></i></a>
                </li>
            <?php } else { ?>
                <li class="nav-item active active">
                    <?php $count = Notifications::unReadCount(); ?>
                    <a class="nav-link" href="<?= Html::encode(Url::to('/notifications/index')) ?>">Уведомления
                        <?php if ($count > 0) { ?>
                            <?php if ($count < 100) { ?>
                                <span class="badge badge-danger"><?= $count; ?></span>
                            <?php } else { ?>
                                <span class="badge badge-danger">100+</span>
                            <?php } ?>
                        <?php } ?>
                    </a>
                </li>
                <li class="nav-item active active">
                    <a class="nav-link" href="<?= Html::encode(Url::to('/site/contact')) ?>">
                        Связаться
                    </a>
                </li>
                <li class="nav-item active dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                       data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <?= $user->getName() ?>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#"><?= Html::beginForm(['/site/logout'], 'post')
                            . Html::submitButton(
                                'Выйти' . '<i class="fas fa-sign-out" style="margin-left: 2px;color: grey;"></i>',
                                ['class' => 'btn', 'style' => 'outline:none']
                            )
                            . Html::endForm() ?></a>
                        <a class="dropdown-item"
                           href="<?= Html::encode(Url::toRoute(['/profile/view', 'id' => Yii::$app->user->id])) ?>">
                            <button class="btn" style="outline: none">Мой профиль <i class="far fa-user-circle"
                                                                                     style="margin-left: 2px;font-size: 16px;color: grey;"></i>
                            </button>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= Html::encode(Url::toRoute(['/profile/liked'])) ?>">
                            <button class="btn" style="outline: none">Понравившееся <i class="fas fa-heart"
                                                                                       style="margin-left: 2px;font-size: 16px"></i>
                            </button>
                        </a>
                        <a class="dropdown-item" href="<?= Html::encode(Url::toRoute(['/profile/starred'])) ?>">
                            <button class="btn" style="outline: none">Закладки <i class="fas fa-star"
                                                                                  style="margin-left: 2px;font-size: 16px"></i>
                            </button>
                        </a>
                    </div>
                </li>
            <?php } ?>
        </ul>
        <form class="form-inline my-2 my-lg-0" method="get" action="<?= Html::encode(Url::toRoute('/site/index')) ?>">
            <input class="form-control mr-sm-2 search-input" name="search" type="search" placeholder="Что сегодня ищем?"
                   aria-label="Search">
            <button class="btn btn-info border-0 search-btn" type="submit"><i class="fas fa-search search-icon"></i>
            </button>
        </form>

    </div>
</nav>
<div class="wrap">

    <div class="container">
        <div class="breadcrumbs">
            <?= \yii\bootstrap4\Breadcrumbs::widget(['links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
        </div>
        <div style="margin-left: 1rem;margin-right: 1rem;"><?= Alert::widget() ?></div>
        <?= $content ?>
    </div>
</div>

<footer class="footer" style="position:fixed;width: 100%;bottom:0;">
    <div class="container">
        <p class="pull-left">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
