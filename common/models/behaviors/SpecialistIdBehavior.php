<?php


namespace common\models\behaviors;

use common\models\users\User;
use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int $maxOrder
 * @property string $cachingKey
 */
class SpecialistIdBehavior extends Behavior
{

    public $attribute = 'specialist_id';

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'assignSpecialist',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'assignSpecialist',
        ];
    }

    public function assignSpecialist()
    {
        /** @var User $user */
        $user = \Yii::$app->user->identity;

        if ($user->specialist) $this->owner->{$this->attribute} = $user->specialist->id;

    }
}