<?php
namespace frontend\controllers;

use common\models\Comments;
use common\models\Posts;
use common\models\Subscriber;
use common\models\User;
use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
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
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionView($id)
    {
        $user = User::findOne($id);

        return $this->render('view', [
            'user' => $user
        ]);
    }

    public function actionUpdate()
    {
        /** @var User $model */
        $model = Yii::$app->user->identity;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionSubscribe($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
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
        Yii::$app->response->format = Response::FORMAT_JSON;
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

    /**
     * Finds the Comments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
