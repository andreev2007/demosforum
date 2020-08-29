<?php

use common\models\User;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Posts */

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

?>

<div class="posts-view">
    <div class="container">
        <div class="row">
            <div class="[ col-xs-12 col-sm-12 ]">
                <div class="[ panel panel-default ] panel-google-plus">
                    <div class="panel-heading">
                        <h4>
                            <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/posts/view', 'id' => $model->id])) ?>"><?= $model->name ?></a>
                        </h4>
                        <h3>
                            Автор:
                            <?php if ($model->user->id === Yii::$app->user->id) { ?>
                                Вы
                            <?php } else { ?>
                                <a href="<?= Html::encode(\yii\helpers\Url::toRoute(['/profile/view', 'id' => $model->user->id])) ?>">

                                    <?= $model->user->username ?>
                                </a>
                            <?php } ?>
                        </h3>
                        <h5><span>Дата</span> - <span><?= $model->getDate() ?></span></h5>
                    </div>
                    <div class="panel-body">
                        <p style="font-size: 110%; color: #504e4e; font-weight: 500">
                            <?= $model->description ?>
                        </p>
                    </div>
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
                        <a class="[ btn btn-default ]" style="float: right">
                            <i class="far fa-comment"></i> <?= $model->getComments()->count() ?>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <?php if (Yii::$app->user->id === $model->user->id) { ?>
            <p>
                <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
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
                in_array($subscriber->id, (array)$repostersId), ['label' => $subscriber->username, 'value' => $subscriber->id]);
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