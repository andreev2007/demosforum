<?php

/* @var $this yii\web\View */

use common\models\User;
use kartik\editors\Summernote;
use mihaildev\ckeditor\CKEditor;
use vova07\imperavi\Widget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\LinkPager;

$user = new User();
$this->title = Yii::t('app', 'Home');
$this->registerMetaTag([
    'name' => 'title',
    'content' => 'Demos Forum, Вуьщы Ащкгь, Demos Forum главная, Демос Форум главная'
])
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <div class="col-lg-12 m-class">
                <?php if (!Yii::$app->user->isGuest) { ?>
                    <h3 style="color: #2f2e2e"><?= Yii::t('app', 'What do you want to write,') ?>
                        <br> <?= $user->getName() ?>
                        ?</h3>
                    <div style="margin-bottom: 1rem;">
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
                                <?= $form->field($post, 'content')->widget(\common\widgets\RedactorCode::className(),
                                    [
                                        'clientOptions' => [
                                            'placeholder' => Yii::t('app', 'Edit your content here...')
                                        ],
                                    ])->label(false); ?>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
                                <div class="col-xs-4 col-sm-4 col-md-12 col-lg-12">
                                    <?= Html::submitButton(Yii::t('app', 'Publish') . '<i class="fab fa-telegram-plane ml-1"></i>', ['class' => 'btn btn-success mb-1 btn-sm']) ?>
                                </div>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                <?php } ?>

                <div class="col-xs-8 col-sm-8 col-md-4 col-lg-4">
                    <h5 class="mb-2"><?= Yii::t('app', 'Posts') ?></h5>
                    <?php $form = ActiveForm::begin([
                        'action' => '/',
                        'options' =>
                            [
                                'id' => 'search-form',
                            ],
                        'method' => "GET",
                    ]); ?>
                    <?= $form->field($searchModel, 'period')->dropDownList([
                        null => Yii::t('app', 'All time'),
                        3600 * 24 => Yii::t('app', 'Day'),
                        3600 * 168 => Yii::t('app', 'Week'),
                        3600 * 720 => Yii::t('app', 'Month'),
                    ], [
                        'onchange' => 'document.getElementById("search-form").submit()',
                    ])->label(false); ?>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <?php foreach ($posts as $question): ?>
                    <div class="[ col-xs-12 col-sm-12 ]">
                        <div class="[ panel panel-default ] panel-google-plus">
                            <div class="panel-heading">
                                <a style="font-size: 110%; color: #504e4e; font-weight: 500; width: 90%;word-break: break-word;"
                                   href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                                    <?= mb_strimwidth($question->content, 0, 200, "..."); ?>
                                </a>
                                <h3>
                                    <?= Yii::t('app', 'Author') ?>:
                                    <?php if ($question->user->id === Yii::$app->user->id) { ?>
                                        <?= Yii::t('app', 'You') ?>
                                    <?php } else { ?>
                                        <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/profile/view', 'id' => $question->user->id])) ?>">

                                            <?= $question->user->first_name . ' ' . $question->user->last_name ?>
                                        </a>
                                    <?php } ?>
                                </h3>
                                <h5><span><?= Yii::t('app', 'Date') ?></span> - <span><?= $question->getDate() ?></span>
                                </h5>
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
                                    <button type="button"
                                            data-post_id='<?= $question->id; ?>'
                                            class="[ btn btn-default ] <?= User::isStarred($question->id) ? 'starred' : 'star' ?>">
                                        <i class="icon-star <?= User::isStarred($question->id) ? 'fas' : 'far' ?> fa-star"
                                           style="margin-right: 2px;"></i>
                                    </button>
                                    <div class="[ btn btn-default ]">
                                        <i class="fas fa-eye" style="margin-right: 2px;"></i>
                                        <?= $question->views ?>
                                    </div>
                                    <a class="[ btn btn-default ]"
                                       href="<?= Html::encode(Url::toRoute(['/posts/share', 'id' => $question->id])) ?>">
                                        <i class="far fa-share-square"></i>
                                    </a>
                                    <a class="[ btn btn-default ]" style="float: right"
                                       href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                                        <i class="far fa-comment-alt"></i> <?= $question->getComments()->count() ?>
                                    </a>
                                </div>
                            <?php } else { ?>
                                <div class="panel-footer">
                                    <a class="[ btn btn-default ]"
                                       href="<?= Html::encode(Url::toRoute(['site/login'])) ?>">
                                        <i class="icon-heart far fa-heart"
                                           style="margin-right: 2px;"></i>
                                        <span class="likes-count">
                                            <?= $question->getLikesCount() ?>
                                        </span>
                                    </a>
                                    <a href="<?= Html::encode(Url::toRoute(['site/login'])) ?>"
                                       class="[ btn btn-default ]">
                                        <i class="icon-star far fa-star"
                                           style="margin-right: 2px;"></i>
                                    </a>
                                    <a class="[ btn btn-default ]" style="float: right"
                                       href="<?= Html::encode(Url::toRoute(['site/login'])) ?>">
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
