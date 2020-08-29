<?php


namespace common\models\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int $maxOrder
 * @property string $cachingKey
 */
class JsonTextBehavior extends Behavior
{

    public $attributes = [];

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'jsonDecode',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'jsonEncode',
        ];
    }

    public function jsonDecode()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->{$attribute} = json_decode($this->owner->{$attribute}, true)?:[];
        }
    }

    public function jsonEncode()
    {
        foreach ($this->attributes as $attribute) {
            $this->owner->{$attribute} = json_encode($this->owner->{$attribute});
        }
    }
}