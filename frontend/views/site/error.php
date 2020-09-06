<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <p>
        <?= Yii::t('app', 'We did not find the page you are searching for') ?>
    </p>
    <p>
        <?= Yii::t('app', 'Perhaps you are searching') ?> <a href="/"><?= Yii::t('app','main page') ?></a>?
    </p>

</div>
