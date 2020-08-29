<?php

use dosamigos\switchinput\SwitchBox;
use dosamigos\switchinput\SwitchRadio;
use nickdenry\grid\FilterContentActionColumn;
use yii\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel common\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?php $form = ActiveForm::begin(); ?>

        <?php ActiveForm::end() ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'id',
                'username',
                'email:email',
                //'status',
                //'verification_token',

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
