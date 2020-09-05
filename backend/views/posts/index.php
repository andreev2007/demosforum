<?php

use nickdenry\grid\FilterContentActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use common\models\User;

$user = new User();
/* @var $this yii\web\View */
/* @var $searchModel common\models\PostsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посты';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-index">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'content',
            [
                'attribute' => 'thumb',
                'format' => 'html',
                'value' => function ($model) {
                    return '<img style="width: 100px" src="' . $model->thumb . '"></img>';
                }
            ],
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',

            [
                'class' => FilterContentActionColumn::className(),
                // Add your own filterContent
                'filterContent' => function () {
                    return '<div class="btn-group"> ' .
                        '</div>';
                },
                /* Another actionColumn options */
            ],
        ],
    ]); ?>


</div>
