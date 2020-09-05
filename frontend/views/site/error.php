<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>
<div class="site-error">

    <div class="alert alert-danger">
        <?= nl2br(Html::encode($message)) ?>
    </div>

    <p>
        Мы не нашли страницу которую вы искали
    </p>
    <p>
        Возможно вы искали <a href="/">Главную страницу</a>?
    </p>

</div>
