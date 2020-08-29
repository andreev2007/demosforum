<?php

namespace common\models\traits;

use yii\helpers\ArrayHelper;

trait MapTrait
{

    public static function map($from, $to)
    {
        return ArrayHelper::map(static::find()->all(), $from, $to);
    }

}