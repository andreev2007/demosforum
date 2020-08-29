<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comment_likes".
 *
 * @property int $id
 * @property int|null $comment_id Комментарий
 * @property int|null $user_id Пользователь
 * @property int|null $created_at
 *
 * @property Comments $comment
 * @property User $user
 */
class CommentLikes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment_likes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment_id', 'user_id', 'created_at'], 'integer'],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comments::className(), 'targetAttribute' => ['comment_id' => 'id']],
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
            'comment_id' => 'Comment ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Comment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comments::className(), ['id' => 'comment_id']);
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
