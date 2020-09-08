<?php

use common\models\User;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app','Subscribed');
?>
<section id="team" class="pb-5">
    <div class="container">
        <div class="row">
            <?php /** @var \yii\data\ActiveDataProvider $dataProvider */
            foreach ($dataProvider->getModels() as $user): ?>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <div class="image-flip">
                        <div class="mainflip flip-0">
                            <div class="frontside">
                                <div class="card">
                                    <div class="card-body text-center">
                                        <?php if ($user->avatar) { ?>
                                           <p><img class="img-fluid" style="border-radius: 100%" src="<?= $user->avatar ?>" alt=""/></p>
                                        <?php } else { ?>
                                           <p><img class="img-fluid" style="border-radius: 100%" src="https://vk.com/images/camera_50.png?ava=1"/></p>
                                        <?php } ?>
                                        <a href="<?= Html::encode(Url::toRoute(['/profile/view', 'id' => $user->id])) ?>"
                                           class="card-title">
                                            <?= $user->first_name . ' ' . $user->last_name ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</section>


<?php
/** @var \common\models\Posts $pagination */
echo \yii\bootstrap4\LinkPager::widget([
    'pagination' => $dataProvider->pagination,
]);
?>
