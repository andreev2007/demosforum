<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

\yii\web\YiiAsset::register($this);

?>

<div class="container">
    <?php if (count($comments) > 0) { ?>
        <h2 class="text-center" style="color: #5f5e5e"><?= Yii::t('app', 'Comments') ?></h2>
    <?php } ?>
    <?php if (!Yii::$app->user->isGuest) { ?>
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($comment, 'name')->textarea(['maxlength' => '450', 'rows' => '4']) ?>

        <div class="form-group" style="display: flex;justify-content: center;">
            <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-share mr-1']) . Yii::t('app', 'Commentate'), ['class' => 'btn btn-success']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    <?php } ?>

    <?php
    if (count($comments) > 0) {
        foreach ($comments as $review) { ?>
            <div class="card" style="margin-bottom: 1rem;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 reply-js">
                            <p>
                                <a class="float-left"
                                   href="<?= Html::encode(Url::toRoute(['/profile/view', 'id' => $review->user->id])) ?>">
                                    <strong><?= $review->user->first_name . ' ' . $review->user->last_name ?></strong>
                                </a>
                                <span class="float-right" style="color: grey">
                                    <?= $review->getDate() ?>
                                </span>
                                <?php if ($review->user->id === Yii::$app->user->id) { ?>
                                    <span class="float-left ml-2">
                                         <?= Html::a(Yii::t('app', 'Update'), ['comment-update', 'id' => $review->id], ['class' => 'btn btn-primary btn-sm']) ?>
                                         <?= Html::a(Yii::t('app', 'Delete'), ['comment-delete', 'id' => $review->id], [
                                             'class' => 'btn btn-danger btn-sm',
                                             'data' => [
                                                 'confirm' => 'Are you sure you want to delete this item?',
                                                 'method' => 'post',
                                             ],
                                         ]) ?>
                                    </span>
                                <?php } ?>
                            </p>
                            <div class="clearfix"></div>
                            <p><?= $review->name ?></p>
                            <?php if (!Yii::$app->user->isGuest) { ?>
                                <p style="margin-bottom: .5rem;">
                                    <button type="button"
                                            data-comment_id='<?= $review->id; ?>'
                                            data-likes_count='<?= $review->getLikesCount(); ?>'
                                            class="[ btn btn-default ] <?= User::isCommentLiked($review->id) ? 'liked-com' : 'like-com' ?>">
                                        <i class="icon-heart <?= User::isCommentLiked($review->id) ? 'fas' : 'far' ?> fa-heart"
                                           style="margin-right: 2px;"></i>
                                        <span class="likes-com-count">
                                         <?= $review->getLikesCount() ?>
                                    </span>
                                    </button>
                                    <button class="float-right btn btn-outline-primary ml-2 reply-btn">
                                        <i class="fa fa-reply"></i>
                                        <?= Yii::t('app', 'Reply') ?>
                                    </button>
                                </p>
                            <?php } else { ?>
                                <p style="margin-bottom: .5rem;">
                                    <a type="button"
                                       data-comment_id='<?= $review->id; ?>'
                                       data-likes_count='<?= $review->getLikesCount(); ?>'
                                       class="[ btn btn-default ]">
                                        <i class="icon-heart fa-heart"
                                           style="margin-right: 2px;"></i>
                                        <span class="likes-com-count">
                                         <?= $review->getLikesCount() ?>
                                    </span>
                                    </a>
                                    <a href="<?= Html::encode(Url::toRoute(['/site/login'])) ?>"
                                       class="float-right btn btn-outline-primary ml-2 reply-btn">
                                        <i class="fa fa-reply"></i>
                                        <?= Yii::t('app', 'Reply') ?>
                                    </a>
                                </p>

                            <?php } ?>
                            <style>
                                .reply-block-hidden {
                                    display: none;
                                }
                            </style>
                            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data',
                                'class' => 'reply-block-hidden js-reply-form']]); ?>

                            <?= $form->field($comment, 'name')->textarea(['maxlength' => '450', 'rows' => '4']) ?>

                            <?= $form->field($comment, 'parent_id')->hiddenInput(['value' => $review->id])->label(false); ?>

                            <div class="form-group">
                                <?= Html::submitButton(Html::tag('i', '', ['class' => 'fa fa-share mr-1']) . Yii::t('app', 'Reply'), ['class' => 'btn btn-success']) ?>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                    <?php /** @var \common\models\Comments $review */
                    foreach ($review->children as $parent_comment) { ?>
                        <div class="card card-inner">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>
                                            <span class="float-right" style="color: grey">
                                            <?= $review->getDate() ?>
                                        </span>
                                            <a href="<?= Html::encode(Url::toRoute(['/profile/view', 'id' => $parent_comment->user->id])) ?>">
                                                <strong><?= $parent_comment->user->first_name . ' ' . $parent_comment->user->last_name ?></strong></a>
                                        </p>

                                        </p>
                                        <p><?= $parent_comment->name ?></p>
                                        <?php if (!Yii::$app->user->isGuest) { ?>
                                            <button type="button"
                                                    data-comment_id='<?= $parent_comment->id; ?>'
                                                    data-likes_count='<?= $parent_comment->getLikesCount(); ?>'
                                                    class="[ btn btn-default ] <?= User::isCommentLiked($parent_comment->id) ? 'liked-com' : 'like-com' ?>">
                                                <i class="icon-heart <?= User::isCommentLiked($parent_comment->id) ? 'fas' : 'far' ?> fa-heart"
                                                   style="margin-right: 2px;"></i>
                                                <span class="likes-com-count">
                                                <?= $parent_comment->getLikesCount() ?>
                                            </span>
                                            </button>
                                        <?php } else { ?>
                                            <a data-comment_id='<?= $parent_comment->id; ?>'
                                               href="<?php Html::encode(Url::toRoute('/site/login')) ?>"
                                               data-likes_count='<?= $parent_comment->getLikesCount(); ?>'
                                               class="[ btn btn-default ]">
                                                <i class="icon-heart fa-heart"
                                                   style="margin-right: 2px;"></i>
                                                <span class="likes-com-count">
                                                <?= $parent_comment->getLikesCount() ?>
                                            </span>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php }
    } else { ?>
        <h4 class="no-comment"><?= Yii::t('app', 'No comments') ?></h4>
    <?php } ?>
</div>