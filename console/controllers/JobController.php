<?php
/**
 * Created by PhpStorm.
 * User: anvik
 * Date: 26.05.2019
 * Time: 10:32
 */


namespace console\controllers;


use common\jobs\mail\MailJob;
use Yii;

class JobController extends \yii\console\Controller
{

    public function actionMail()
    {

        foreach (range(1, 1) as $item) {
            Yii::$app->queue->push(new MailJob([
                'to' => 'an.viktory@gmail.com',
            ]));
        }

    }

}