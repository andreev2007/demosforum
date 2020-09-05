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
    <div>
        <h4 style="color: #212020"><?= Yii::t('app', 'Change language') ?></h4>
        <?= \lajax\languagepicker\widgets\LanguagePicker::widget([
            'itemTemplate' => '<li><a href="{link}" class="nav-link" title="{language}"><i id="{language}"></i> <span style="color: black">{name}</span></a></li>',
            'activeItemTemplate' => '<a href="{link}"  class="btn btn-primary"  title="{language}"><i id="{language}" ></i> <span>{name}</span></a>',
            'parentTemplate' => '<li class="language-picker dropdown-list {size} "><a>{activeItem}<ul>{items}</ul></a></li>',
            'languageAsset' => 'lajax\languagepicker\bundles\LanguageLargeIconsAsset',      // StyleSheets
            'languagePluginAsset' => 'lajax\languagepicker\bundles\LanguagePluginAsset',    // JavaScripts
        ]); ?>
    </div>
    <div class="mt-3">
        <h4 style="color: #212020"><?= Yii::t('app', 'Change password') ?></h4>
        <a href="<?= Html::encode(Url::toRoute(['/site/request-password-reset'])) ?>"
           class="btn btn-primary"><?= Yii::t('app', 'Change password') ?></a>
    </div>
    <div class="mt-3">
        <h4 style="color: #212020"><?= Yii::t('app', 'Change avatar') ?></h4>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($user, 'avatarUpload')->fileInput()->label(false) ?>
        <?= Html::submitButton(Yii::t('app', 'Save'),['class' => 'btn btn-success mb-1']) ?>

        <?php ActiveForm::end() ?>
    </div>
</div>
