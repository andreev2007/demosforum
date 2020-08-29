<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<div class="site-error">

    <div class="alert alert-danger">
        <?= nl2br(Html::encode('Страница не найдена')) ?>
    </div>

    <p>
        Извините, но мы не нашли страницу которую вы искали
    </p>
    <p>
        Может быть вы искали <a href="/">Главную страницу</a>?
    </p>

</div>
