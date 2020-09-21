<?php

use common\models\Notifications;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NotificationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Notifications');
?>
<div class="notifications-index m-class">

    <h1 class="text-center mb-3" style="margin-top: -50px;"><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div class="container-fluid">
        <div class="row">
            <?php foreach ($dataProvider->getModels() as $notification) { ?>
                <div class="col-6" style="background: #efecec;padding-top: 10px;border-bottom: 1px solid white;">
                    <p><?= Notifications::map()[$notification->type] ?></p>
                </div>
                <div class="col-6" style="background: #efecec;padding-top: 10px;border-bottom: 1px solid white;">
                    <a href="<?= Url::toRoute(['/posts/view', 'id' => $notification->post->id]) ?>">
                        <?= mb_strimwidth($notification->post->content, 0, 20, "...") ?>
                    </a>
                    <?php if (isset($notification->post->created_at)) { ?>
                    <p style="font-size: 12px; color: grey; float: right"><?= $notification->post->getDate() ?></p>
                    <?php } ?>
                </div>
            <?php } ?>

        </div>
    </div>


</div>
