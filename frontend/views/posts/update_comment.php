<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Comments */

$this->title = 'Редактировать: ' . strip_tags(strip_tags($model->name));

?>
<div class="posts-update m-class">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest) { ?>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <div class="row mt-3">
            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                <?= $form->field($model, 'name')->textarea(['maxlength' => '450', 'rows' => '4'])->label(false) ?>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                <div class="col-xs-4 col-sm-4 col-md-12 col-lg-12">
                    <?= Html::submitButton(Yii::t('app', 'Update') . '<i class="fab fa-telegram-plane ml-1"></i>', ['class' => 'btn btn-success mb-1 btn-sm']) ?>
                </div>
            </div>
        </div>


        <?php ActiveForm::end(); ?>

    <?php } ?>

</div>
