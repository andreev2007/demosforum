<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Сохраненные';
?>

<div class="container">
    <div class="row">
        <?php /** @var \yii\data\ActiveDataProvider $dataProvider */
        foreach ($dataProvider->getModels() as $post): ?>
            <div class="[ col-xs-12 col-sm-12 ]">
                <div class="[ panel panel-default ] panel-google-plus">
                    <div class="panel-heading">
                        <h4>
                            <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/posts/view', 'id' => $post->id])) ?>"><?= $post->name ?></a>
                        </h4>
                        <h3>
                            Автор:
                            <?php if ($post->user->id === Yii::$app->user->id) { ?>
                                Вы
                            <?php } else { ?>
                                <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/profile/view', 'id' => $post->user->id])) ?>">

                                    <?= $post->user->username ?>
                                </a>
                            <?php } ?>
                        </h3>
                        <h5><span>Дата</span> - <span><?= $post->getDate() ?></span></h5>
                    </div>
                    <div class="panel-body">
                        <p style="font-size: 110%; color: #504e4e; font-weight: 500">
                            <?= mb_strimwidth($post->description, 0, 200, "..."); ?>
                        </p>
                    </div>
                    <div class="panel-footer">
                        <button type="button"
                                data-post_id='<?= $post->id; ?>'
                                data-likes_count='<?= $post->getLikesCount(); ?>'
                                class="[ btn btn-default ] <?= User::isLiked($post->id) ? 'liked' : 'like' ?>">
                            <i class="icon-heart <?= User::isLiked($post->id) ? 'fas' : 'far' ?> fa-heart"
                               style="margin-right: 2px;"></i>
                            <span class="likes-count">
                                         <?= $post->getLikesCount() ?>
                                    </span>
                        </button>
                        <button type="button"
                                data-post_id='<?= $post->id; ?>'
                                class="[ btn btn-default ] <?= User::isStarred($post->id) ? 'starred' : 'star' ?>">
                            <i class="icon-star <?= User::isStarred($post->id) ? 'fas' : 'far' ?> fa-star"
                               style="margin-right: 2px;"></i>
                        </button>
                        <button type="button" class="[ btn btn-default ]">
                            <i class="far fa-share-square"></i>
                        </button>
                        <a class="[ btn btn-default ]" style="float: right" href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $post->id])) ?>">
                            <i class="far fa-comment-alt"></i> <?= $post->getComments()->count() ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<?php
/** @var \common\models\Posts $pagination */
echo \yii\bootstrap4\LinkPager::widget([
    'pagination' => $dataProvider->pagination,
]);
?>
