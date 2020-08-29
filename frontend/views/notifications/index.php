<?php

use common\models\Notifications;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\NotificationsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Уведомления';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="notifications-index m-class">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'type',
                'filter' => Notifications::map(),
                'value' => function ($model) {
                    return Notifications::map()[$model->type] ?? '';
                }
            ],
            [
                'format' => 'raw',
                'attribute' => 'post_id',
                'value' => function ($model) {
                    return Html::a(mb_strimwidth($model->post->name, 0, 30, "..."), ['/posts/view', 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>


</div>
