<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string $name Имя
 * @property int|null $post_id Имя
 * @property int|null $likes Лайк
 * @property int|null $parent_id Родительский комментарий
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Comments $parent
 * @property Comments[] $comments
 * @property Posts $post
 * @property Comments[]|null children
 */
class Comments extends \yii\db\ActiveRecord
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
        return 'comments';
    }

    public function getLikesCount()
    {
        $count = $this->getLikes()->count();
        if ($count === null) {
            $count = 0;
        }
        return $count;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'created_by'], 'required'],
            [['post_id', 'likes', 'parent_id', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comments::className(), 'targetAttribute' => ['parent_id' => 'id']],
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
            'name' => Yii::t('app', 'Comment'),
            'post_id' => 'Имя',
            'likes' => 'Лайк',
            'parent_id' => 'Родительский комментарий',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[Parent]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comments::className(), ['id' => 'parent_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['parent_id' => 'id']);
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
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getLikes()
    {
        return $this->hasMany(CommentLikes::className(), ['comment_id' => 'id']);
    }

    public function getParentComments()
    {
        return $this->hasOne(Comments::className(), ['id' => 'parent_id']);
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }

    public static function addComment($id)
    {
        $comment = new Comments();
        $comment->post_id = $id;
        $comment->created_by = Yii::$app->user->id;
        return $comment;
    }

    public function getChildren()
    {
        return $this->hasMany(self::class, ['parent_id' => 'id']);
    }

    public function afterSave($insert, $changedAttributes)
    {
        $note = Notifications::create(
            'comment',
            $this->post_id,
            $this->created_by = $this->post->created_by
        );

        return parent::afterSave($insert, $changedAttributes);
    }
}
