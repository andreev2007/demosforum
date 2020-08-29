<?php

/* @var $this yii\web\View */

use common\models\User;
use mihaildev\ckeditor\CKEditor;
use vova07\imperavi\Widget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$user = new User();
$this->title = 'Главная';
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-12 m-class">
                <?php if (!Yii::$app->user->isGuest) { ?>
                    <h2>Что хотите написать, <?= $user->getName() ?>?</h2>

                    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                    <?= $form->field($post, 'name')->textInput(); ?>

                    <?= $form->field($post, 'description')->widget(CKEditor::className(), [
                        'editorOptions' => [
                            'preset' => 'full',
                            'lang' => 'ru',
                            'inline' => false,
                            'height' => '150'
                        ],
                    ]); ?>

                    <?= $form->field($post, 'imageUpload')->fileInput(); ?>

                    <div class="form-group">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                <?php } ?>

                <div class="col-4">
                    <?php $form = ActiveForm::begin([
                        'action' => '/',
                        'options' =>
                            [
                                'id' => 'search-form',
                            ],
                        'method' => "GET",
                    ]); ?>
                    <?= $form->field($searchModel, 'period')->dropDownList([
                        null => 'За все время',
                        3600 * 24 => 'За день',
                        3600 * 168 => 'За неделю',
                        3600 * 720 => 'За месяц',
                    ], [
                        'onchange' => 'document.getElementById("search-form").submit()',
                    ]); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php foreach ($posts as $question): ?>
                    <div class="[ col-xs-12 col-sm-12 ]">
                        <div class="[ panel panel-default ] panel-google-plus">
                            <img src="<?= $question->image ?>" style="width: 100%;" alt="">
                            <div class="panel-heading">
                                <h4>
                                    <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/posts/view', 'id' => $question->id])) ?>"><?= $question->name ?></a>
                                </h4>
                                <h3>
                                    Автор:
                                    <?php if ($question->user->id === Yii::$app->user->id) { ?>
                                        Вы
                                    <?php } else { ?>
                                        <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/profile/view', 'id' => $question->user->id])) ?>">

                                            <?= $question->user->username ?>
                                        </a>
                                    <?php } ?>
                                </h3>
                                <h5><span>Дата</span> - <span><?= $question->getDate() ?></span></h5>
                            </div>
                            <div class="panel-body">
                                <p style="font-size: 110%; color: #504e4e; font-weight: 500">
                                    <?= mb_strimwidth($question->description, 0, 200, "..."); ?>
                                </p>
                            </div>
                            <?php if (!Yii::$app->user->isGuest) { ?>
                                <div class="panel-footer">
                                    <button type="button"
                                            data-post_id='<?= $question->id; ?>'
                                            data-likes_count='<?= $question->getLikesCount(); ?>'
                                            class="[ btn btn-default ] <?= User::isLiked($question->id) ? 'liked' : 'like' ?>">
                                        <i class="icon-heart <?= User::isLiked($question->id) ? 'fas' : 'far' ?> fa-heart"
                                           style="margin-right: 2px;"></i>
                                        <span class="likes-count">
                                         <?= $question->getLikesCount() ?>
                                    </span>
                                    </button>
                                    <a class="[ btn btn-default ]"
                                       href="<?= Html::encode(Url::toRoute(['/posts/share', 'id' => $question->id])) ?>">
                                        <i class="far fa-share-square"></i>
                                    </a>
                                    <button type="button"
                                            data-post_id='<?= $question->id; ?>'
                                            class="[ btn btn-default ] <?= User::isStarred($question->id) ? 'starred' : 'star' ?>">
                                        <i class="icon-star <?= User::isStarred($question->id) ? 'fas' : 'far' ?> fa-star"
                                           style="margin-right: 2px;"></i>
                                    </button>
                                    <a class="[ btn btn-default ]" style="float: right"
                                       href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                                        <i class="far fa-comment-alt"></i> <?= $question->getComments()->count() ?>
                                    </a>
                                </div>
                            <?php } else { ?>
                                <div class="panel-footer">
                                    <div class="[ btn btn-default ]">
                                        <i class="icon-heart far fa-heart"
                                           style="margin-right: 2px;"></i>
                                        <span class="likes-count">
                                            <?= $question->getLikesCount() ?>
                                        </span>
                                    </div>
                                    <div class="[ btn btn-default ]">
                                        <i class="icon-star far fa-star"
                                           style="margin-right: 2px;"></i>
                                    </div>
                                    <a class="[ btn btn-default ]" style="float: right"
                                         href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                                        <i class="far fa-comment-alt"></i> <?= $question->getComments()->count() ?>
                                    </a>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div style="margin-left: 1rem;margin-right: 1rem;">
            <?php
            /** @var \common\models\Posts $pagination */
            echo \yii\bootstrap4\LinkPager::widget([
                'pagination' => $pagination,
            ]);
            ?>
        </div>
    </div>
</div>
