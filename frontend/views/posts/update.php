<?php

use mihaildev\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Posts */

$this->title = 'Обновить: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновить';
?>
<div class="posts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (!Yii::$app->user->isGuest) { ?>

        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->widget(CKEditor::className(), [
            'editorOptions' => [
                'preset' => 'full',
                'lang' => 'ru',
                'inline' => false,
                'height' => '150'
            ],
        ]); ?>
        <?= $form->field($model, 'imageUpload')->fileInput(['maxlength' => true, 'prompt']) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    <?php } ?>

</div>
