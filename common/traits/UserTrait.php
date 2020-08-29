<?php

namespace common\traits;

use Yii;

trait UserTrait
{
    public function getName()
    {
        if(!Yii::$app->user->isGuest) {
            $username = \Yii::$app->user->identity->username;

        } else {
            $username = null;
        }
        return $username;
    }
}