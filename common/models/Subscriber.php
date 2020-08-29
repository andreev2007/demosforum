<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "subscriber".
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $subscriber_id
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $subscriber
 * @property User $user
 */
class Subscriber extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subscriber';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'subscriber_id', 'created_at', 'updated_at'], 'integer'],
            [['subscriber_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['subscriber_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'subscriber_id' => 'Subscriber ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Subscriber]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriber()
    {
        return $this->hasOne(User::className(), ['id' => 'subscriber_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
