<?php


namespace common\models\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int $maxOrder
 * @property string $cachingKey
 */
class ShowValidationErrorsBehavior extends Behavior
{
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_VALIDATE => 'logErrors'
        ];
    }

    public function logErrors() {
       if ($this->owner->errors) {
           \Yii::error('ERRORS');
           \Yii::error($this->owner->errors);
       }
    }
}