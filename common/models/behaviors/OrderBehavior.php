<?php


namespace common\models\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

/**
 *
 * @property int $maxOrder
 * @property string $cachingKey
 */
class OrderBehavior extends Behavior
{

    public $orderAttribute = 'order';
    public $where = null;


    public function reorder($direction = '')
    {
        /** @var ActiveRecord $class */
        $class = get_class($this->owner);
        if ($direction == 'up') {
            $this->owner->{$this->orderAttribute} = $this->owner->{$this->orderAttribute} - 1.5;

            \Yii::error("SET TO $this->orderAttribute OF ".$this->owner->id." VALUE ". $this->owner->{$this->orderAttribute});

        } elseif ($direction == 'down') {
            $this->owner->{$this->orderAttribute} = $this->owner->{$this->orderAttribute} + 1.5;
            \Yii::error("SET TO $this->orderAttribute OF ".$this->owner->id." VALUE ". $this->owner->{$this->orderAttribute});
        }
        $this->owner->update();
        $this->resetOrder();

    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function resetOrder(): void
    {
        /** @var ActiveRecord $class */
        $class = get_class($this->owner);
        // get all related $records
        if ($records = $class::find()->where($this->whereCondition())->orderBy($this->orderAttribute)->all()) {
            foreach ($records as $key => $record) {
                $class::updateAll([$this->orderAttribute => ($key + 1)],['id' =>  $record->id]);
               /* $record->{$this->orderAttribute} = $key + 1;
                $record->update(false);*/
            }
        }
    }


    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'nextOrder',
            ActiveRecord::EVENT_AFTER_DELETE => 'resetOrder'
        ];
    }

    public function whereCondition()
    {
        $whereCondition = [];
        if ($this->where) {
            if (isset($this->where['AND'])) {
                foreach ($this->where['AND'] as $attribute)
                    $whereCondition[$attribute] = $this->owner->{$attribute};
            }
            $whereCondition = ['AND', $whereCondition];

        }
        return $whereCondition;
    }

    public function getMaxOrder()
    {
        /** @var ActiveRecord $class */
        $class = get_class($this->owner);
        $primaryKey = $class::primaryKey();

        /** @var ActiveRecord $class */
        $class = get_class($this->owner);
        $maxValue = $class::find()
            ->andFilterWhere($this->whereCondition() ?: [$primaryKey => null])
            ->select($this->orderAttribute)
            ->orderBy([$this->orderAttribute => SORT_DESC])
            ->asArray()->one();
        return $maxValue[$this->orderAttribute] ?: 0;
    }

    public function nextOrder()
    {
        $this->owner->{$this->orderAttribute} = $this->maxOrder ? $this->getMaxOrder() + 1 : 1;

    }


}