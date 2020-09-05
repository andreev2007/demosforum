<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "notifications".
 *
 * @property int $id
 * @property string $type Пост
 * @property int $post_id Пост
 * @property int $user_id Пост
 * @property int $is_read
 * @property int $created_at Добавлен
 * @property int $created_by Добавлен
 */
class Notifications extends \yii\db\ActiveRecord
{

    public static function map()
    {
        return [
            'post_like' => Yii::t('app', 'Like'),
            'repost' => Yii::t('app', 'Repost'),
            'comment' => Yii::t('app', 'Comment'),
        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'updatedByAttribute' => false
            ],
            [
                'class' => TimestampBehavior::className(),
                'updatedAtAttribute' => false
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notifications';
    }

    public function getPost()
    {
        return $this->hasOne(Posts::className(), ['id' => 'post_id']);
    }

    public static function create($type, $post_id, $user_id)
    {
        $notification = new static();
        $notification->type = $type;
        $notification->post_id = $post_id;
        $notification->user_id = $user_id;
        $notification->save();
        Yii::error($notification->errors);
        return $notification;
    }

    public static function unReadCount()
    {
        return self::find()->where(['user_id' => Yii::$app->user->id])->andWhere(['is_read' => false])->count();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'post_id'], 'required'],
            [['post_id', 'is_read', 'created_at', 'created_by'], 'integer'],
            ['created_at', 'default', 'value' => time()],
            [['type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => Yii::t('app', 'Type'),
            'post_id' => Yii::t('app', 'Post'),
            'is_read' => 'Прочитано',
            'created_at' => 'Создано в',
            'created_by' => 'Created By',
        ];
    }

    public static function getNotifications($dataProvider)
    {
        return $ids = array_map(function ($model) {
            return $model->id;
        }, $dataProvider->getModels());
    }
}
