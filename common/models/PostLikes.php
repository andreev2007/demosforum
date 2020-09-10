<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "post_likes".
 *
 * @property int $post_id Пост
 * @property int $user_id Пользователь
 * @property int|null $like_count
 * @property int $updated_at Обновлен
 *
 * @property Posts $post
 * @property User $user
 */
class PostLikes extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post_likes';
    }

    /**subscriber
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
            'post_id' => 'Пост',
            'user_id' => 'Пользователь',
            'created_at' => 'Обновлен',
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

    public function getDate()
    {
        return Yii::$app->formatter->asDatetime($this->created_at);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $note = Notifications::create(
            'post_like',
            $this->post_id,
            $this->user_id = $this->post->created_by
        );

        return parent::afterSave($insert, $changedAttributes);
    }
}
