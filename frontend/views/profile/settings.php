<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$user = new User();
$this->title = Yii::t('app', 'Settings');
?>
<h1 class="text-center mb-3" style="margin-top: -50px;"><?= $this->title ?></h1>

<div class="m-class">
    <div class="mt-3">
        <a href="<?= Html::encode(Url::toRoute(['/site/request-password-reset'])) ?>"
           class="btn btn-primary"><?= Yii::t('app', 'Change password') ?></a>
    </div>
    <div class="mt-3">
        <div>
            <?= Html::a(Yii::t('app', 'Edit Profile'), ['update'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
