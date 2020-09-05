<?php

use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Posts */

$this->title = strip_tags(strip_tags($model->content));
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<div class="posts-view">
    <div class="container">
        <div class="row">
            <div class="[ col-xs-12 col-sm-12 ]">
                <div class="[ panel panel-default ] panel-google-plus">
                    <div class="panel-heading">
                        <p style="font-size: 110%; color: #504e4e; font-weight: 500">
                            <?= strip_tags(strip_tags($model->content)) ?>
                        </p>
                        <h3>
                            <?= Yii::t('app', 'Author') ?>:
                            <?php if ($model->user->id === Yii::$app->user->id) { ?>
                                <?= Yii::t('app','You') ?>
                            <?php } else { ?>
                                <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/profile/view', 'id' => $model->user->id])) ?>">

                                    <?= $model->user->username ?>
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
                            <a class="[ btn btn-default ]"
                               href="<?= Html::encode(Url::toRoute(['/posts/share', 'id' => $model->id])) ?>">
                                <i class="far fa-share-square"></i>
                            </a>
                            <button type="button"
                                    data-post_id='<?= $model->id; ?>'
                                    class="[ btn btn-default ] <?= User::isStarred($model->id) ? 'starred' : 'star' ?>">
                                <i class="icon-star <?= User::isStarred($model->id) ? 'fas' : 'far' ?> fa-star"
                                   style="margin-right: 2px;"></i>
                            </button>
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
                <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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


<?= $this->render('comments', [
    'comment' => $comment,
    'comments' => $comments,
]) ?>
