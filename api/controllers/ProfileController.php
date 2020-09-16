<?php
namespace api\controllers;

use common\models\Comments;
use common\models\Posts;
use common\models\Subscriber;
use common\models\User;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class ProfileController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['authenticator']['authMethods'] = [
            HttpBasicAuth::className(),
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        return $behaviors;
    }

    public function actionView()
    {
        return $this->findModel();
    }

    public function actionUpdate()
    {
        $model = $this->findModel();

        $model->load(Yii::$app->request->getBodyParams(), '');
        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }

    public function verbs()
    {
        return [
            'view' => ['get'],
            'update' => ['put', 'patch'],
        ];
    }


    public function actionSubscribe($id)
    {
        $params = ['user_id' => $id, 'subscriber_id' => Yii::$app->user->id];
        if ($model = Subscriber::findOne($params)) {
            Yii::$app->response->setStatusCode(404);
            return [
                'subscribersCount' => User::findOne($id)->getSubscribersCount(),
            ];
        } else {
            $model = new Subscriber($params);
            $model->save();
            Yii::$app->response->setStatusCode(201);
            return [
                'subscribersCount' => User::findOne($id)->getSubscribersCount()
            ];
        }
    }

    public function actionUnSubscribe($id)
    {
        
        $params = ['user_id' => $id, 'subscriber_id' => Yii::$app->user->id];
        if ($model = Subscriber::findOne($params)) {
            $model->delete();
            Yii::$app->response->setStatusCode(201);
            return [
                'subscribersCount' => User::findOne($id)->getSubscribersCount()
            ];
        } else {
            Yii::$app->response->setStatusCode(404);
            return [
                'subscribersCount' => User::findOne($id)->getSubscribersCount()
            ];
        }

    }

    private function findModel()
    {
        return User::findOne(\Yii::$app->user->id);
    }
}
