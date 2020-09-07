<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$this->title = $user->first_name . ' ' . $user->last_name ;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>
<h1 class="m-class"><?= Yii::t('app', 'Profile') ?></h1>
<div class="m-class" style="margin-bottom: 1rem;">
    <h6 class="profile-count-posts"><?= Yii::t('app', 'Number of records') ?>: <?= count($user->posts) ?></h6>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4">
            <div class="card">
                <div class="card-body">
                    <div class="block-header">
                        <?php if ($user->avatar) { ?>
                            <img class="profile-image" src="<?= $user->avatar ?>" alt="" />
                        <?php } else { ?>
                            <img class="profile-image" src="https://vk.com/images/camera_50.png?ava=1"/>
                        <?php } ?>

                        <h5 class="card-title profile-name"><?= Html::encode($user->first_name . ' ' . $user->last_name) ?></h5>
                    </div>
                    <div class="subscription-block">
                        <span class="card-text"><?= Yii::t('app', 'Subscribers') ?>: <span
                                    class="subscribers-count"><?= $user->getSubscribersCount() ?></span></span>
                        <span class="card-text"><?= Yii::t('app', 'Subscribed') ?>: <span
                                    class="subscribed-count"><?= $user->getSubscribedCount() ?></span></span>
                    </div>
                    <div style="display: flex;" class="justify-content-between">
                        <?php if (Yii::$app->user->id === $user->id) { ?>
                            <div>
                                <?= Html::a(Yii::t('app', 'Update'), ['update'], ['class' => 'btn btn-primary']) ?>
                            </div>
                        <?php } ?>
                        <?php if (!Yii::$app->user->isGuest) { ?>
                            <?php if ($user->id !== Yii::$app->user->id) { ?>
                                <div>
                                    <?php if (User::isSubscribed($user->id) !== $user->id) { ?>
                                        <button class="btn btn-outline-danger subscribe"
                                                data-user_id="<?= $user->id; ?>"
                                                data-subscribers_count='<?= $user->getSubscribersCount() ?>'
                                        >
                                            Подписаться
                                        </button>
                                    <?php } elseif (User::isSubscribed($user->id) === $user->id) { ?>
                                        <button class="btn btn-secondary subscribed"
                                                data-user_id="<?= $user->id; ?>"
                                                data-subscribers_count='<?= $user->getSubscribersCount() ?>'
                                        >
                                            Отписаться
                                        </button>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8 mt-2">
            <?php foreach ($user->posts as $question): ?>
                <div class="[ panel panel-default ] panel-google-plus">
                    <div class="panel-heading">
                        <a style="font-size: 110%; color: #504e4e; font-weight: 500; width: 90%"
                           href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                            <?= mb_strimwidth($question->content, 0, 200, "..."); ?>
                        </a>
                        <h5><span><?= Yii::t('app', 'Date') ?></span> - <span><?= $question->getDate() ?></span></h5>
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
            <?php endforeach; ?>

        </div>
    </div>
</div>
<br>