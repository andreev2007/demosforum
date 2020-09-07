<?php

namespace common\traits;

use Yii;

trait UserTrait
{
    public function getName()
    {
        if(!Yii::$app->user->isGuest) {
            $username = \Yii::$app->user->identity->first_name . \Yii::$app->user->identity->last_name;

        } else {
            $username = null;
        }
        return $username;
    }
}