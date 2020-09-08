<?php
namespace frontend\controllers;

use common\models\Subscriber;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class ProfileController extends Controller
{
    public function actionView($id)
    {
        $user = User::findOne($id);

        return $this->render('view', [
            'user' => $user
        ]);
    }

    public function actionLiked()
    {
        $user = Yii::$app->user->identity;
        $dataProvider = new ActiveDataProvider(
            [
                'query' =>  $user->getLikedPosts()
            ]
        );

        return $this->render('liked', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSettings(){
        $user = User::findOne(Yii::$app->user->id);

        return $this->render('settings', [
            'user' => $user
        ]);
    }

    public function actionStarred()
    {
        $user = Yii::$app->user->identity;
        $dataProvider = new ActiveDataProvider(
            [
                'query' =>  $user->getStarredPosts()
            ]
        );

        return $this->render('starred', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubscribed()
    {
        $user = Yii::$app->user->identity;
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $user->getSubscribed()
            ]
        );

        return $this->render('subscribed', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSubscribers()
    {
        $user = Yii::$app->user->identity;
        $dataProvider = new ActiveDataProvider(
            [
                'query' => $user->getSubscribers()
            ]
        );

        return $this->render('subscribers', [
            'user' => $user,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionUpdate()
    {
        $model = User::findOne(Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        Yii::error($model->errors);
        return $this->render('update', [
            'model' => $model
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
