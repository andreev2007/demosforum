<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$user = new User();
$this->title = Yii::t('app', 'Home');

$this->title = Yii::t('app', 'Settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="m-class">
    <div class="mt-3">
        <h4 style="color: #212020"><?= Yii::t('app', 'Change password') ?></h4>
        <a href="<?= Html::encode(Url::toRoute(['/site/request-password-reset'])) ?>"
           class="btn btn-primary"><?= Yii::t('app', 'Change password') ?></a>
    </div>
    <div class="mt-3">
        <h4 style="color: #212020"><?= Yii::t('app', 'Change avatar') ?></h4>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($user, 'avatarUpload')->fileInput()->label(false) ?>
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success mb-1']) ?>

        <?php ActiveForm::end() ?>
    </div>
    <div class="mt-3">
        <div>
            <?= Html::a(Yii::t('app', 'Edit Profile'), ['update'], ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
</div>
