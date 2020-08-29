<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "user_saved".
 *
 * @property int $post_id Пост
 * @property int $user_id Пользователь
 * @property int $created_at Добавлен
 *
 * @property Posts $post
 * @property User $user
 */
class UserSaved extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_saved';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'user_id'], 'required'],
            [['post_id', 'user_id', 'created_at'], 'integer'],
            ['created_at', 'default', 'value' => time()],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['post_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'post_id' => 'Post ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
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
