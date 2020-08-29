<?php

use nickdenry\grid\FilterContentActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CommentsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Комментарии';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'name',
            'post_id',
            'likes',
            'parent_id',
            //'created_by',
            //'updated_by',
            //'created_at',
            //'updated_at',

            [
                'class' => FilterContentActionColumn::className(),
                // Add your own filterContent
                'filterContent' => function()
                {
                    return '<div class="btn-group"> '.
                        '</div>';
                },
                /* Another actionColumn options */
            ],
        ],
    ]); ?>


</div>
