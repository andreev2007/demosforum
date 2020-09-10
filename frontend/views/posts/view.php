<?php

use common\models\Posts;
use common\models\User;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Posts */

$this->title = mb_strimwidth(strip_tags(strip_tags($model->content)), 0, 50, '...');
\yii\web\YiiAsset::register($this);

?>
<?= $model->views ?>
<div class="posts-view">
    <div class="container">
        <div class="row">
            <div class="[ col-xs-12 col-sm-12 ]">
                <div class="[ panel panel-default ] panel-google-plus">
                    <div class="panel-heading">
                        <a href="#" style="text-decoration: none;font-size: 110%; color: #504e4e; font-weight: 500;width: 90%;word-break: break-word;">
                            <?= strip_tags($model->content) ?>
                        </a>
                        <h3>
                            <?= Yii::t('app', 'Author') ?>:
                            <?php if ($model->user->id === Yii::$app->user->id) { ?>
                                <?= Yii::t('app', 'You') ?>
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
                                <?php if ($model->views == null) { ?>
                                    0
                                <?php } else { ?>
                                    <?= $model->views ?>
                                <?php } ?>
                            </div>
                            <a class="[ btn btn-default ]"
                               href="<?= Html::encode(Url::toRoute(['/posts/share', 'id' => $model->id])) ?>">
                                <i class="far fa-share-square"></i>
                            </a>
                            <a class="[ btn btn-default ]" style="float: right"
                               href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $model->id])) ?>">
                                <i class="far fa-comment-alt"></i> <?= $model->getComments()->count() ?>
                            </a>
                        </div>
                    <?php } else { ?>
                        <div class="panel-footer">
                            <a href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $model->id])) ?>"
                               class="[ btn btn-default ]">
                                <i class="icon-heart far fa-heart"
                                   style="margin-right: 2px;"></i>
                                <span class="likes-count">
                                            <?= $model->getLikesCount() ?>
                                        </span>
                            </a>
                            <a href="<?= Html::encode(Url::toRoute(['/posts/view', 'id' => $model->id])) ?>"
                               class="[ btn btn-default ]">
                                <i class="icon-star far fa-star"
                                   style="margin-right: 2px;"></i>
                            </a>
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
