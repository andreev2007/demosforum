<?php

namespace common\models;

use common\models\traits\MapTrait;
use common\traits\UserTrait;
use lhs\Yii2SaveRelationsBehavior\SaveRelationsBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $avatar
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property Posts|[] posts
 * @property mixed|null posts
 * @property mixed|null first_name
 * @property mixed|null last_name
 * @property mixed|null phone
 * @property mixed|null telegram
 * @property mixed|null whatsapp
 * @method getImageFileUrl(string $string)
 * @method getThumbFileUrl(string $string, string $string1)
 */
class User extends ActiveRecord implements IdentityInterface
{
    use UserTrait;

    const STATUS_INACTIVE = 9;
    const STATUS_ACTIVE = 10;
    private $filePath = '';
    public $avatarUpload;
    /**
     * @var mixed|null
     */
    public $thumb;


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            [
                'class' => ImageUploadBehavior::class,
                'attribute' => 'avatarUpload',
                'thumbs' => [
                    'thumb' => ['width' => 1000, 'height' => 1000],
                ],
                'filePath' => '@frontend/web/avatars/' . $this->getImagePath() . '.[[extension]]',
                'fileUrl' => '/avatars/' . $this->getImagePath() . '.[[extension]]',
                'thumbPath' => '@frontend/web/avatars/thumb/' . $this->getImagePath() . '.[[extension]]',
                'thumbUrl' => '/avatars/thumb/' . $this->getImagePath() . '.[[extension]]',
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'avatarUpload',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'avatarUpload',
                ],
                'value' => function ($event) {
                    /** @var Posts $post */
                    $post = $event->sender;
                    return $post->getImageFileUrl('avatarUpload');
                },
            ],

            [
                'class' => SaveRelationsBehavior::class,
                'relations' => ['reposters']
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
          'first_name' => Yii::t('app','First Name'),
          'last_name' => Yii::t('app','Last Name'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    public static function isLiked($postId)
    {
        return PostLikes::findOne(['post_id' => $postId, 'user_id' => Yii::$app->user->id]);
    }

    public static function isCommentLiked($commentId)
    {
        return CommentLikes::findOne(['comment_id' => $commentId, 'user_id' => Yii::$app->user->id]);
    }

    public static function isSubscribed($userId)
    {
        return Subscriber::findOne(['user_id' => $userId, 'subscriber_id' => Yii::$app->user->id]);
    }

    public static function isStarred($postId)
    {
        return UserSaved::findOne(['post_id' => $postId, 'user_id' => Yii::$app->user->id]);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_INACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE]],
            [['email', 'first_name', 'last_name','phone','telegram','whatsapp'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function isAdmin()
    {
        return Yii::$app->user->can('admin');
    }

    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'status' => self::STATUS_INACTIVE
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Generates new token for email verification
     */
    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function getSubscribersCount()
    {
       return Subscriber::find()->where(['user_id' => $this->id])->count();
    }

    public function getSubscribedCount()
    {
        $count = $this->getSubscribed()->count();
        if ($count === null) {
            $count = 0;
        }
        return $count;
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['created_by' => 'id']);
    }

    public function getSubscribers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->via('subscribersAttachment');
    }

    public function getSubscribersAttachment()
    {
        return $this->hasMany(Subscriber::className(), ['subscriber_id' => 'id']);
    }

    public function getSubscribed()
    {
        return $this->hasMany(Subscriber::className(), ['subscriber_id' => 'id']);
    }

    public function getLiked()
    {
        return $this->hasMany(PostLikes::className(), ['user_id' => 'id']);
    }

    public function getStarred()
    {
        return $this->hasMany(UserSaved::className(), ['user_id' => 'id']);
    }

    public function getLikedPosts()
    {
        return $this->hasMany(Posts::className(), ['id' => 'post_id'])->via('liked');
    }

    public function getStarredPosts()
    {
        return $this->hasMany(Posts::className(), ['id' => 'post_id'])->via('starred');
    }

    public function afterSave($insert, $changedAttributes)
    {
        if ($this->status == 9) {
            Posts::updateAll(['status' => 9], ['created_by' => $this->id]);
        }
        if ($this->status == 10) {
            Posts::updateAll(['status' => 10], ['created_by' => $this->id]);
        }
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }

    public function getImagePath()
    {
        return $this->filePath ?: $this->filePath = date('Y') . "/" . date('m-d') . "/" . Yii::$app->security->generateRandomString(6);
    }

    public function beforeSave($insert)
    {
        if ($this->getImageFileUrl('avatarUpload')) {
            Yii::error($this->getImageFileUrl('avatarUpload'));
            $this->avatar = $this->getImageFileUrl('avatarUpload');
            $this->thumb = $this->getThumbFileUrl('avatarUpload', 'thumb');
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

}
