<?php

/* @var $this \yii\web\View */

/* @var $content string */

use common\models\User;
use yii\helpers\Html;

backend\assets\FontAwesomeAsset::register($this);
backend\assets\AdminLteAsset::register($this);
$this->registerCssFile('https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700');

$assetDir = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
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
<body class="hold-transition sidebar-mini">
<?php $this->beginBody() ?>

<div class="wrapper">
    <?php  if (User::isAdmin()) { ?>
    <?= $this->render('navbar', ['assetDir' => $assetDir]) ?>

    <?= $this->render('sidebar', ['assetDir' => $assetDir]) ?>

    <?php } ?>


    <?= $this->render('content', ['content' => $content, 'assetDir' => $assetDir]) ?>

    <?php if (User::isAdmin()) { ?>
    <?= $this->render('footer') ?>
    <?php } ?>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
