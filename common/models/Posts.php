<?php

namespace common\models;

use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use mohorev\file\UploadBehavior;
use Yii;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\data\Pagination;
use yii\db\ActiveRecord;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $content Описание
 * @property string|null $image Картинка
 * @property int|null $likes Лайки
 * @property int $created_by
 * @property int $updated_by
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Comments[] $comments
 * @property Reposts[] $reposts
 * @method getImageFileUrl(string $string)
 * @method getThumbFileUrl(string $string, string $string1)
 */
class Posts extends \yii\db\ActiveRecord
{
    private $filePath = '';
    public $imageUpload;
    /**
     * @var mixed|null
     */
    public $thumb;

    public function behaviors()
    {
        return [
            BlameableBehavior::className(),
            TimestampBehavior::className(),
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'imageUpload',
                'thumbs' => [
                    'thumb' => ['width' => 1000, 'height' => 1000],
                ],
                'filePath' => '@frontend/web/images/' . $this->getImagePath() . '.[[extension]]',
                'fileUrl' => '/images/'. $this->getImagePath() . '.[[extension]]',
                'thumbPath' => '@frontend/web/images/thumb/' . $this->getImagePath() . '.[[extension]]',
                'thumbUrl' => '/images/thumb/'. $this->getImagePath() .'.[[extension]]',
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'imageUpload',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'imageUpload',
                ],
                'value' => function ($event) {
                    /** @var Posts $post */
                    $post = $event->sender;
                    return $post->getImageFileUrl('imageUpload');
                },
            ],

            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['reposters']
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    public function getImage() {
        return Yii::getAlias('@frontend/web/images/') . $this->getImagePath();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'required'],
            [['created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['content'], 'string', 'max' => 1000],
            [['reposters', 'image','thumb'], 'safe'],
            ['imageUpload', 'file', 'extensions' => 'jpeg, jpg, png', 'on' => ['insert', 'update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content' => Yii::t('app', 'Description'),
            'image' => Yii::t('app', 'Image'),
            'imageUpload' => Yii::t('app', 'Upload Image'),
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'period' => 'Период',
        ];
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }

    public function getLikesCount()
    {
        $count = $this->getLikes()->count();
        if ($count === null) {
            $count = 0;
        }
        return $count;
    }

    public function getStarredCount()
    {
        $count = $this->getStarred()->count();
        if ($count === null) {
            $count = 0;
        }
        return $count;
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Reposts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReposts()
    {
        return $this->hasMany(Reposts::className(), ['post_id' => 'id']);
    }

    /**
     * Gets query for [[Reposts]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReposters()
    {
        return $this->hasMany(User::className(), ['id' => 'owner_id'])->via('reposts');
    }

    public function getLikes()
    {
        return $this->hasMany(PostLikes::className(), ['post_id' => 'id']);
    }


    public function getStarred()
    {
        return $this->hasMany(UserSaved::className(), ['post_id' => 'id']);
    }

    public static function getAll()
    {
        $query = Posts::find()->orderBy(['created_at' => SORT_DESC]);
        $count = $query->count();
        $pagination = new Pagination(['totalCount' => $count, 'pageSize' => 8]);

        $posts = $query->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        $data['posts'] = $posts;
        $data['pagination'] = $pagination;

        return $data;
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getImagePath()
    {
        return $this->filePath ?: $this->filePath = date('Y') . "/" . date('m-d') . "/" . Yii::$app->security->generateRandomString(6);
    }

    public function beforeSave($insert)
    {
        if ($this->getImageFileUrl('imageUpload')) {
            Yii::error($this->getImageFileUrl('imageUpload'));
            $this->image = $this->getImageFileUrl('imageUpload');
            $this->thumb = $this->getThumbFileUrl('imageUpload', 'thumb');
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }


}
