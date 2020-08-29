<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "reposts".
 *
 * @property int $id
 * @property int $post_id
 * @property int|null $owner_id
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property User $owner
 * @property Posts $post
 */
class Reposts extends \yii\db\ActiveRecord
{
    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
        ];
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reposts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['post_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'required'],
            [['post_id', 'owner_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['owner_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Posts::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'post_id' => 'Post ID',
            'owner_id' => 'Owner ID',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'owner_id']);
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


    public function afterSave($insert, $changedAttributes)
    {
        $note = Notifications::create(
            'repost',
            $this->post_id,
            $this->created_by = $this->post->created_by
        );

        return parent::afterSave($insert, $changedAttributes);
    }
}
