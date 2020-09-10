<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\StringHelper;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user common\models\User */

\yii\web\YiiAsset::register($this);
$this->title = $user->first_name
?>
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">
            <div class="card">
                <div class="card-body">
                    <div class="block-header">
                        <?php if ($user->avatar) { ?>
                            <img class="profile-image" src="<?= $user->avatar ?>" alt=""/>
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
                        <span class="card-text"><?= Yii::t('app', 'Posts') ?>: <span
                                    class="subscribed-count"><?= count($user->posts) ?></span></span>
                    </div>
                    <div>
                        <?php if ($user->phone) { ?>
                            <div>
                                <span><?= Yii::t('app','Phone') . ': ' . $user->phone ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($user->whatsapp) { ?>
                            <div>
                                <span><?= 'Whatsapp' . ': ' . $user->whatsapp ?></span>
                            </div>
                        <?php } ?>
                        <?php if ($user->telegram) { ?>
                            <div>
                                <span><?= 'Telegram' . ': ' . $user->telegram ?></span>
                            </div>
                        <?php } ?>
                    </div>
                    <div style="display: flex;" class="justify-content-between">
                        <?php if (!Yii::$app->user->isGuest) { ?>
                            <?php if ($user->id !== Yii::$app->user->id) { ?>
                                <div>
                                    <?php if (!User::isSubscribed($user->id)) { ?>
                                        <button class="btn btn-outline-danger subscribe"
                                                data-user_id="<?= $user->id; ?>"
                                                data-subscribers_count='<?= $user->getSubscribersCount() ?>'
                                        >
                                            Подписаться
                                        </button>
                                    <?php } else { ?>
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

        <div class="col-sm-12 col-md-12 col-lg-8 col-xl-8">
            <?php foreach ($user->posts as $question): ?>
                <div class="[ panel panel-default ] panel-google-plus">
                    <a href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>"
                       class="panel-heading">
                        <a style="font-size: 110%; color: #504e4e; font-weight: 500;width: 90%;word-break: break-word;"
                           href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                            <?= mb_strimwidth($question->content, 0, 200, "..."); ?>
                        </a>
                        <h5><span><?= Yii::t('app', 'Date') ?></span> - <span><?= $question->getDate() ?></span></h5>
                    </a>
                    <?php if (!Yii::$app->user->isGuest) { ?>
                        <a href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>"
                           class="panel-footer">
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
                                <?php if ($question->views == null) { ?>
                                    0
                                <?php } else { ?>
                                    <?= $question->views ?>
                                <?php } ?>
                            </div>
                            <a class="[ btn btn-default ]"
                               href="<?= Html::encode(Url::toRoute(['/posts/share', 'id' => $question->id])) ?>">
                                <i class="far fa-share-square"></i>
                            </a>
                            <a class="[ btn btn-default ]" style="float: right"
                               href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $question->id])) ?>">
                                <i class="far fa-comment-alt"></i> <?= $question->getComments()->count() ?>
                            </a>
                        </a>
                    <?php } else { ?>
                        <div class="panel-footer">
                            <a href="<?= Html::encode(Url::toRoute(['site/login'])) ?>" class="[ btn btn-default ]">
                                <i class="icon-heart far fa-heart"
                                   style="margin-right: 2px;"></i>
                                <span class="likes-count">
                                            <?= $question->getLikesCount() ?>
                                        </span>
                            </a>
                            <a href="<?= Html::encode(Url::toRoute(['site/login'])) ?>" class="[ btn btn-default ]">
                                <i class="icon-star far fa-star"
                                   style="margin-right: 2px;"></i>
                            </a>

                            <div class="[ btn btn-default ]">
                                <i class="fas fa-eye" style="margin-right: 2px;"></i>
                                <?php if ($question->views == null) { ?>
                                    0
                                <?php } else { ?>
                                    <?= $question->views ?>
                                <?php } ?>
                            </div>
                            <a class="[ btn btn-default ]" style="float: right"
                               href="<?= Html::encode(Url::toRoute(['site/login'])) ?>">
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