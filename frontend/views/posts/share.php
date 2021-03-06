<?php

use common\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Posts */

$this->title = strip_tags(mb_strimwidth($model->content, 0, 100, "..."));
\yii\web\YiiAsset::register($this);

?>

<div class="posts-view">
    <div class="container">
        <div class="row">
            <div class="[ col-xs-12 col-sm-12 ]">
                <div class="[ panel panel-default ] panel-google-plus">
                    <div class="panel-heading">
                        <a style="font-size: 110%; color: #504e4e; font-weight: 500; width: 90%;word-break: break-word;"
                           href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $model->id])) ?>">
                            <?= $model->content ?>
                        </a>
                        <h3>
                            <?= Yii::t('app', 'Author') ?>:
                            <?php if ($model->user->id === Yii::$app->user->id) { ?>
                                <?= Yii::t('app','You') ?>
                            <?php } else { ?>
                                <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/profile/view', 'id' => $model->user->id])) ?>">

                                    <?= $model->user->first_name . ' ' . $model->user->last_name ?>
                                </a>
                            <?php } ?>
                        </h3>
                        <h5><span><?= Yii::t('app', 'Date') ?></span> - <span><?= $model->getDate() ?></span></h5>
                    </div>
                    <?php if (!Yii::$app->user->isGuest) { ?>
                        <div class="panel-footer">
                            <button type="button"
                                    data-post_id='<?= $model->id; ?>'
                                    data-likes_count='<?= $model->getLikesCount(); ?>'
                                    class="[ btn btn-default ] <?= User::isLiked($model->id) ? 'liked' : 'like' ?>">
                                <i class="icon-heart <?= User::isLiked($model->id) ? 'fas' : 'far' ?> fa-heart"
                                   style="margin-right: 2px;"></i>
                                <span class="likes-count">
                                         <?= $model->getLikesCount() ?>
                                </span>
                            </button>
                            <button type="button"
                                    data-post_id='<?= $model->id; ?>'
                                    class="[ btn btn-default ] <?= User::isStarred($model->id) ? 'starred' : 'star' ?>">
                                <i class="icon-star <?= User::isStarred($model->id) ? 'fas' : 'far' ?> fa-star"
                                   style="margin-right: 2px;"></i>
                            </button>

                            <div class="[ btn btn-default ]">
                                <i class="fas fa-eye" style="margin-right: 2px;"></i>
                                <?= $model->views ?>
                            </div>
                            <a class="[ btn btn-default ]" style="float: right"
                               href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $model->id])) ?>">
                                <i class="far fa-comment-alt"></i> <?= $model->getComments()->count() ?>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="panel-footer">
                            <div class="[ btn btn-default ]">
                                <i class="icon-heart far fa-heart"
                                   style="margin-right: 2px;"></i>
                                <span class="likes-count">
                                            <?= $model->getLikesCount() ?>
                                        </span>
                            </div>
                            <div class="[ btn btn-default ]">
                                <i class="icon-star far fa-star"
                                   style="margin-right: 2px;"></i>
                            </div>

                            <div class="[ btn btn-default ]">
                                <i class="fas fa-eye" style="margin-right: 2px;"></i>
                                <?= $model->views ?>
                            </div>
                            <a class="[ btn btn-default ]" style="float: right"
                               href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $model->id])) ?>">
                                <i class="far fa-comment-alt"></i> <?= $model->getComments()->count() ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>
        <?php if (Yii::$app->user->id === $model->user->id) { ?>
            <p>
                <?= Html::a('<i class="fas fa-pencil-alt mr-1"></i>'.Yii::t('app','Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('<i class="fas fa-trash-alt mr-1"></i>'.Yii::t('app','Delete'), ['delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => 'Are you sure you want to delete this item?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p>

        <?php } ?>
    </div>

</div>
<br>

<div style="margin-left: 1rem;margin-right: 1rem;">
    <?php
    /** @var User $user */
    $user = Yii::$app->user->identity;

    /** @var User $subscriber */
    $form = ActiveForm::begin(); ?>

    <div class="share-subscriber-block">
        <?php
        foreach ($user->getSubscribers()->all() as $subscriber) {
            echo Html::checkbox('reposters[]',
                in_array($subscriber->id, (array)$repostersId), ['label' => $subscriber->first_name . ' ' . $subscriber->last_name, 'value' => $subscriber->id]);
        }
        ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Отправить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<style>

    .share-subscriber-block > label {
        margin-right: .5rem;
        margin-bottom: 1rem;
    }
</style>